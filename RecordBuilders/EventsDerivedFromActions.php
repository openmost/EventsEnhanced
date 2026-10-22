<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\EventsEnhanced\RecordBuilders;

use Piwik\ArchiveProcessor;
use Piwik\ArchiveProcessor\Record;
use Piwik\ArchiveProcessor\RecordBuilder;
use Piwik\Container\StaticContainer;
use Piwik\DataAccess\LogAggregator;
use Piwik\DataAccess\LogQueryBuilder;
use Piwik\DataTable;
use Piwik\Metrics;
use Piwik\Plugin\Manager;
use Piwik\Plugins\EventsEnhanced\Archiver;
use Piwik\Tracker\Action;

/**
 * The events GA4 collects automatically, rebuilt from Matomo actions, visits, Media Analytics plays and Form
 * Analytics forms. They are
 * not Matomo events: they complement the Events reports and never mix with them.
 */
class EventsDerivedFromActions extends RecordBuilder
{
    public const SOURCE_VISITS = 'visits';
    public const SOURCE_NEW_VISITS = 'new_visits';
    public const SOURCE_PAGEVIEWS = 'pageviews';
    public const SOURCE_OUTLINKS = 'outlinks';
    public const SOURCE_DOWNLOADS = 'downloads';
    public const SOURCE_SITE_SEARCH = 'site_search';
    public const SOURCE_VIDEO_PLAYS = 'video_plays';
    public const SOURCE_VIDEO_PROGRESS = 'video_progress';
    public const SOURCE_VIDEO_COMPLETE = 'video_complete';
    public const SOURCE_FORM_STARTS = 'form_starts';
    public const SOURCE_FORM_SUBMISSIONS = 'form_submissions';

    public const METADATA_SOURCE = 'source';

    public const EVENT_NAMES = [
        self::SOURCE_VISITS => 'session_start',
        self::SOURCE_NEW_VISITS => 'first_visit',
        self::SOURCE_PAGEVIEWS => 'page_view',
        self::SOURCE_OUTLINKS => 'click',
        self::SOURCE_DOWNLOADS => 'file_download',
        self::SOURCE_SITE_SEARCH => 'view_search_results',
        self::SOURCE_VIDEO_PLAYS => 'video_start',
        self::SOURCE_VIDEO_PROGRESS => 'video_progress',
        self::SOURCE_VIDEO_COMPLETE => 'video_complete',
        self::SOURCE_FORM_STARTS => 'form_start',
        self::SOURCE_FORM_SUBMISSIONS => 'form_submit',
    ];

    // MediaAnalytics::MEDIA_TYPE_VIDEO, not referenced so the plugin works without Media Analytics
    private const MEDIA_TYPE_VIDEO = 1;

    public function __construct()
    {
        parent::__construct();

        $this->columnToSortByBeforeTruncation = Metrics::INDEX_EVENT_NB_HITS;
    }

    public function getRecordMetadata(ArchiveProcessor $archiveProcessor): array
    {
        return [
            Record::make(Record::TYPE_BLOB, Archiver::RECORD_EVENTS_DERIVED_FROM_ACTIONS),
        ];
    }

    protected function aggregate(ArchiveProcessor $archiveProcessor): array
    {
        $logAggregator = $archiveProcessor->getLogAggregator();

        $metrics = $this->queryVisits($logAggregator) + $this->queryActionTypes($logAggregator);
        if ($this->isPluginActivated('MediaAnalytics')) {
            $metrics += $this->queryVideoPlays($archiveProcessor, $logAggregator);
        }
        if ($this->isPluginActivated('FormAnalytics')) {
            $metrics += $this->queryForms($archiveProcessor, $logAggregator);
        }

        $record = new DataTable();
        foreach (self::EVENT_NAMES as $source => $eventName) {
            if (empty($metrics[$source][Metrics::INDEX_EVENT_NB_HITS])) {
                continue;
            }

            $record->addRowFromArray([
                DataTable\Row::COLUMNS => ['label' => $eventName] + $metrics[$source],
                DataTable\Row::METADATA => [self::METADATA_SOURCE => $source],
            ]);
        }

        return [Archiver::RECORD_EVENTS_DERIVED_FROM_ACTIONS => $record];
    }

    private function queryVisits(LogAggregator $logAggregator): array
    {
        $visits = 0;
        $newVisits = 0;

        $resultSet = $logAggregator->queryVisitsByDimension(['visitor_returning']);
        while ($row = $resultSet->fetch()) {
            $count = (int) $row[Metrics::INDEX_NB_VISITS];
            $visits += $count;
            if ((int) $row['visitor_returning'] === 0) {
                $newVisits += $count;
            }
        }

        return [
            self::SOURCE_VISITS => $this->metrics($visits, $visits),
            self::SOURCE_NEW_VISITS => $this->metrics($newVisits, $newVisits),
        ];
    }

    /**
     * Same action selection as the core Actions reports: page views, outlinks and downloads by the type of
     * idaction_url, site searches by the type of idaction_name (it wins over a URL tracked with the search),
     * events excluded.
     */
    private function queryActionTypes(LogAggregator $logAggregator): array
    {
        $sourceByType = [
            Action::TYPE_PAGE_URL => self::SOURCE_PAGEVIEWS,
            Action::TYPE_OUTLINK => self::SOURCE_OUTLINKS,
            Action::TYPE_DOWNLOAD => self::SOURCE_DOWNLOADS,
            Action::TYPE_SITE_SEARCH => self::SOURCE_SITE_SEARCH,
        ];

        $select = "
                case when log_action_name.type = " . Action::TYPE_SITE_SEARCH . " then log_action_name.type else log_action_url.type end as actionType,
                count(distinct log_link_visit_action.idvisit) as `" . Metrics::INDEX_NB_VISITS . "`,
                count(*) as `" . Metrics::INDEX_EVENT_NB_HITS . "`
        ";

        $from = [
            "log_link_visit_action",
            [
                "table"      => "log_action",
                "tableAlias" => "log_action_url",
                "joinOn"     => "log_link_visit_action.idaction_url = log_action_url.idaction",
            ],
            [
                "table"      => "log_action",
                "tableAlias" => "log_action_name",
                "joinOn"     => "log_link_visit_action.idaction_name = log_action_name.idaction",
            ],
        ];

        $where  = $logAggregator->getWhereStatement('log_link_visit_action', 'server_time');
        $where .= " AND log_link_visit_action.idaction_event_category IS NULL";
        $where .= " AND (log_action_url.type IN (" . Action::TYPE_PAGE_URL . ", " . Action::TYPE_OUTLINK . ", " . Action::TYPE_DOWNLOAD . ")"
            . " OR log_action_name.type = " . Action::TYPE_SITE_SEARCH . ")";

        $query = $logAggregator->generateQuery($select, $from, $where, 'actionType', '');
        $resultSet = $logAggregator->getDb()->query($query['sql'], $query['bind']);

        $metrics = [];
        while ($row = $resultSet->fetch()) {
            $source = $sourceByType[(int) $row['actionType']] ?? null;
            if ($source !== null) {
                $metrics[$source] = $this->metrics((int) $row[Metrics::INDEX_EVENT_NB_HITS], (int) $row[Metrics::INDEX_NB_VISITS]);
            }
        }

        return $metrics;
    }

    /**
     * GA4 video events from the Media Analytics video plays (same play selection and segment handling as Media
     * Analytics): video_start per play, video_progress per 10, 25, 50 and 75% milestone the furthest position of
     * a play reached, video_complete per play that reached the end (the player "ended" event stops one second
     * before the length at most).
     */
    private function queryVideoPlays(ArchiveProcessor $archiveProcessor, LogAggregator $logAggregator): array
    {
        $percent = "(LEAST(log_media.media_progress, log_media.media_length) * 100 / log_media.media_length)";
        $milestones = "(" . $percent . " >= 10) + (" . $percent . " >= 25) + (" . $percent . " >= 50) + (" . $percent . " >= 75)";
        $isComplete = "log_media.media_length > 0 AND log_media.media_progress >= log_media.media_length - 1";
        $hasLength = "log_media.media_length > 0";

        $select = "
                count(*) as plays,
                count(distinct log_media.idvisit) as play_visits,
                sum(case when " . $hasLength . " then " . $milestones . " else 0 end) as progress,
                count(distinct case when " . $hasLength . " and " . $percent . " >= 10 then log_media.idvisit end) as progress_visits,
                sum(case when " . $isComplete . " then 1 else 0 end) as complete,
                count(distinct case when " . $isComplete . " then log_media.idvisit end) as complete_visits
        ";

        $where  = $logAggregator->getWhereStatement('log_media', 'server_time');
        $where .= " AND log_media.media_type = " . self::MEDIA_TYPE_VIDEO . " AND log_media.watched_time > 1";

        $row = $this->fetchPluginLogRow($archiveProcessor, $logAggregator, $select, 'log_media', $where, 'log_media.idview');

        return [
            self::SOURCE_VIDEO_PLAYS => $this->metrics((int) ($row['plays'] ?? 0), (int) ($row['play_visits'] ?? 0)),
            self::SOURCE_VIDEO_PROGRESS => $this->metrics((int) ($row['progress'] ?? 0), (int) ($row['progress_visits'] ?? 0)),
            self::SOURCE_VIDEO_COMPLETE => $this->metrics((int) ($row['complete'] ?? 0), (int) ($row['complete_visits'] ?? 0)),
        ];
    }

    /**
     * GA4 form events from the Form Analytics forms, counted like the Form Analytics "Form starts" and
     * "Form submissions" metrics.
     */
    private function queryForms(ArchiveProcessor $archiveProcessor, LogAggregator $logAggregator): array
    {
        $select = "
                sum(log_form.num_starts) as starts,
                count(distinct case when log_form.num_starts > 0 then log_form.idvisit end) as start_visits,
                sum(log_form.num_submissions) as submissions,
                count(distinct case when log_form.num_submissions > 0 then log_form.idvisit end) as submission_visits
        ";

        $where = $logAggregator->getWhereStatement('log_form', 'form_last_action_time');

        $row = $this->fetchPluginLogRow($archiveProcessor, $logAggregator, $select, 'log_form', $where, 'log_form.idlogform');

        return [
            self::SOURCE_FORM_STARTS => $this->metrics((int) ($row['starts'] ?? 0), (int) ($row['start_visits'] ?? 0)),
            self::SOURCE_FORM_SUBMISSIONS => $this->metrics((int) ($row['submissions'] ?? 0), (int) ($row['submission_visits'] ?? 0)),
        ];
    }

    /**
     * One aggregated row from the log table of another plugin. Under a segment the joins could repeat a log row,
     * so the rows are first grouped by their own id, as Media Analytics does.
     */
    private function fetchPluginLogRow(ArchiveProcessor $archiveProcessor, LogAggregator $logAggregator, string $select, string $table, string $where, string $rowId): array
    {
        $logQueryBuilder = StaticContainer::get(LogQueryBuilder::class);
        $segment = $archiveProcessor->getParams()->getSegment();
        $forceInnerGroupBy = $segment && $segment->getString();
        if ($forceInnerGroupBy) {
            $logQueryBuilder->forceInnerGroupBySubselect($rowId);
        }

        try {
            $query = $logAggregator->generateQuery($select, [$table], $where, '', '');
        } finally {
            // it would otherwise apply to the archiving queries of other plugins
            if ($forceInnerGroupBy) {
                $logQueryBuilder->forceInnerGroupBySubselect('');
            }
        }

        return $logAggregator->getDb()->query($query['sql'], $query['bind'])->fetch() ?: [];
    }

    protected function isPluginActivated(string $plugin): bool
    {
        return Manager::getInstance()->isPluginActivated($plugin);
    }

    private function metrics(int $events, int $visits): array
    {
        return [
            Metrics::INDEX_EVENT_NB_HITS => $events,
            Metrics::INDEX_NB_VISITS => $visits,
        ];
    }
}
