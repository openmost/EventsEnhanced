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
use Piwik\Config\GeneralConfig;
use Piwik\DataAccess\LogAggregator;
use Piwik\DataTable;
use Piwik\Metrics;
use Piwik\Plugins\Actions\ArchivingHelper;
use Piwik\Plugins\Events\Archiver as EventsArchiver;
use Piwik\Plugins\EventsEnhanced\Archiver;
use Piwik\RankingQuery;
use Piwik\SettingsPiwik;
use Piwik\Tracker\Action;

/**
 * RecordBuilder for 3-level event hierarchy: Category -> Action -> Name
 * Follows Events plugin pattern for building nested subtables
 */
class EventsAllDimensions extends RecordBuilder
{
    public function __construct()
    {
        parent::__construct();

        $this->columnToSortByBeforeTruncation = Metrics::INDEX_NB_VISITS;

        // Column aggregation operations for metrics (same as Events)
        $this->columnAggregationOps = [
            Metrics::INDEX_EVENT_MIN_EVENT_VALUE => 'min',
            Metrics::INDEX_EVENT_MAX_EVENT_VALUE => 'max',
        ];
    }

    public function getRecordMetadata(ArchiveProcessor $archiveProcessor): array
    {
        $idSite = $archiveProcessor->getParams()->getSite()->getId();
        $maximumRowsInDataTable = GeneralConfig::getConfigValue('datatable_archiving_maximum_rows_events', $idSite);
        $maximumRowsInSubDataTable = GeneralConfig::getConfigValue('datatable_archiving_maximum_rows_subtable_events', $idSite);

        $record = Record::make(Record::TYPE_BLOB, Archiver::RECORD_EVENTS_ALL_DIMENSIONS);
        $record->setMaxRowsInTable($maximumRowsInDataTable)
            ->setMaxRowsInSubtable($maximumRowsInSubDataTable)
            ->setBlobColumnAggregationOps($this->columnAggregationOps);

        return [$record];
    }

    protected function aggregate(ArchiveProcessor $archiveProcessor): array
    {
        $logAggregator = $archiveProcessor->getLogAggregator();

        $record = new DataTable();

        // Build query exactly like Events plugin
        $select = "
                log_action_event_category.name as eventCategory,
                log_action_event_action.name as eventAction,
                log_action_event_name.name as eventName,

                count(distinct log_link_visit_action.idvisit) as `" . Metrics::INDEX_NB_VISITS . "`,
                count(distinct log_link_visit_action.idvisitor) as `" . Metrics::INDEX_NB_UNIQ_VISITORS . "`,
                count(*) as `" . Metrics::INDEX_EVENT_NB_HITS . "`,

                sum(
                    case when " . Action::DB_COLUMN_CUSTOM_FLOAT . " is null
                        then 0
                        else " . Action::DB_COLUMN_CUSTOM_FLOAT . "
                    end
                ) as `" . Metrics::INDEX_EVENT_SUM_EVENT_VALUE . "`,
                sum( case when " . Action::DB_COLUMN_CUSTOM_FLOAT . " is null then 0 else 1 end )
                    as `" . Metrics::INDEX_EVENT_NB_HITS_WITH_VALUE . "`,
                min(" . Action::DB_COLUMN_CUSTOM_FLOAT . ") as `" . Metrics::INDEX_EVENT_MIN_EVENT_VALUE . "`,
                max(" . Action::DB_COLUMN_CUSTOM_FLOAT . ") as `" . Metrics::INDEX_EVENT_MAX_EVENT_VALUE . "`
        ";

        $from = [
            "log_link_visit_action",
            [
                "table"      => "log_action",
                "tableAlias" => "log_action_event_category",
                "joinOn"     => "log_link_visit_action.idaction_event_category = log_action_event_category.idaction",
            ],
            [
                "table"      => "log_action",
                "tableAlias" => "log_action_event_action",
                "joinOn"     => "log_link_visit_action.idaction_event_action = log_action_event_action.idaction",
            ],
            [
                "table"      => "log_action",
                "tableAlias" => "log_action_event_name",
                "joinOn"     => "log_link_visit_action.idaction_name = log_action_event_name.idaction",
            ],
        ];

        $where  = $logAggregator->getWhereStatement('log_link_visit_action', 'server_time');
        $where .= " AND log_link_visit_action.idaction_event_category IS NOT NULL";

        $groupBy = "log_link_visit_action.idaction_event_category,
                    log_link_visit_action.idaction_event_action,
                    log_link_visit_action.idaction_name";

        $orderBy = "`" . Metrics::INDEX_NB_VISITS . "` DESC, `eventName`";

        // Apply ranking query if configured
        $rankingQueryLimit = ArchivingHelper::getRankingQueryLimit();
        $rankingQuery = null;
        if ($rankingQueryLimit > 0) {
            $rankingQuery = new RankingQuery($rankingQueryLimit);
            $rankingQuery->addLabelColumn(['eventCategory', 'eventAction', 'eventName']);
            $rankingQuery->addColumn([Metrics::INDEX_NB_UNIQ_VISITORS]);
            $rankingQuery->addColumn([Metrics::INDEX_EVENT_NB_HITS, Metrics::INDEX_NB_VISITS, Metrics::INDEX_EVENT_NB_HITS_WITH_VALUE], 'sum');
            $rankingQuery->addColumn(Metrics::INDEX_EVENT_SUM_EVENT_VALUE, 'sum');
            $rankingQuery->addColumn(Metrics::INDEX_EVENT_MIN_EVENT_VALUE, 'min');
            $rankingQuery->addColumn(Metrics::INDEX_EVENT_MAX_EVENT_VALUE, 'max');
        }

        // Generate and execute query
        $query = $logAggregator->generateQuery($select, $from, $where, $groupBy, $orderBy);

        if ($rankingQuery) {
            $query['sql'] = $rankingQuery->generateRankingQuery($query['sql']);
        }

        $resultSet = $logAggregator->getDb()->query($query['sql'], $query['bind']);

        if ($resultSet === false) {
            return [Archiver::RECORD_EVENTS_ALL_DIMENSIONS => $record];
        }

        while ($row = $resultSet->fetch()) {
            $this->aggregateEventRow($record, $row);
        }

        return [Archiver::RECORD_EVENTS_ALL_DIMENSIONS => $record];
    }

    /**
     * Aggregate a single event row into the 3-level hierarchy
     * Level 1: Category (main table)
     * Level 2: Action (subtable of Category)
     * Level 3: Name (subtable of Action)
     */
    protected function aggregateEventRow(DataTable $record, array $row): void
    {
        $category = $row['eventCategory'] ?? '';
        $action = $row['eventAction'] ?? '';
        $name = $row['eventName'] ?? '';

        if (empty($category)) {
            return;
        }

        // Handle empty event name
        if (empty($name)) {
            $name = EventsArchiver::EVENT_NAME_NOT_SET;
        }

        $columns = [
            Metrics::INDEX_NB_UNIQ_VISITORS         => $row[Metrics::INDEX_NB_UNIQ_VISITORS] ?? 0,
            Metrics::INDEX_NB_VISITS                => $row[Metrics::INDEX_NB_VISITS] ?? 0,
            Metrics::INDEX_EVENT_NB_HITS            => $row[Metrics::INDEX_EVENT_NB_HITS] ?? 0,
            Metrics::INDEX_EVENT_NB_HITS_WITH_VALUE => $row[Metrics::INDEX_EVENT_NB_HITS_WITH_VALUE] ?? 0,
            Metrics::INDEX_EVENT_SUM_EVENT_VALUE    => round($row[Metrics::INDEX_EVENT_SUM_EVENT_VALUE] ?? 0, 2),
            Metrics::INDEX_EVENT_MIN_EVENT_VALUE    => is_numeric($row[Metrics::INDEX_EVENT_MIN_EVENT_VALUE]) ? round($row[Metrics::INDEX_EVENT_MIN_EVENT_VALUE], 2) : null,
            Metrics::INDEX_EVENT_MAX_EVENT_VALUE    => is_numeric($row[Metrics::INDEX_EVENT_MAX_EVENT_VALUE]) ? round($row[Metrics::INDEX_EVENT_MAX_EVENT_VALUE], 2) : null,
        ];

        // Level 1: Sum to Category row
        $categoryRow = $record->sumRowWithLabel($category, $columns, $this->columnAggregationOps);

        // Level 2: Sum to Action subtable (under Category)
        if (!empty($action)) {
            $actionRow = $categoryRow->sumRowWithLabelToSubtable($action, $columns, $this->columnAggregationOps);

            // Level 3: Sum to Name subtable (under Action)
            $actionRow->sumRowWithLabelToSubtable($name, $columns, $this->columnAggregationOps);
        }
    }

    /**
     * Override buildForNonDayPeriod to compute true unique visitors when enabled for the period.
     * By default, Matomo sums daily unique visitors (which is inaccurate).
     * When isUniqueVisitorsEnabled is true for the period, we re-query log tables directly.
     */
    public function buildForNonDayPeriod(ArchiveProcessor $archiveProcessor): void
    {
        $periodLabel = $archiveProcessor->getParams()->getPeriod()->getLabel();

        // Check if unique visitors calculation is enabled for this period
        if (SettingsPiwik::isUniqueVisitorsEnabled($periodLabel)) {
            // Re-query log tables directly to get accurate unique visitors
            $this->buildFromLogs($archiveProcessor);
        } else {
            // Fall back to default aggregation (sums daily values, renames nb_uniq_visitors)
            parent::buildForNonDayPeriod($archiveProcessor);
        }
    }
}
