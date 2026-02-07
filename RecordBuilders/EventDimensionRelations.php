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
use Piwik\Container\StaticContainer;
use Piwik\DataAccess\LogAggregator;
use Piwik\DataTable;
use Piwik\Metrics;
use Piwik\Plugins\Actions\ArchivingHelper;
use Piwik\Plugins\EventsEnhanced\Archiver;
use Piwik\RankingQuery;
use Psr\Log\LoggerInterface;

/**
 * RecordBuilder for event dimension-to-dimension relationships
 * Creates records that link event dimensions together at the action level
 * (e.g., which actions occur within a category)
 */
class EventDimensionRelations extends RecordBuilder
{
    public function __construct()
    {
        parent::__construct();
        $this->columnToSortByBeforeTruncation = Metrics::INDEX_NB_VISITS;
    }

    public function getRecordMetadata(ArchiveProcessor $archiveProcessor): array
    {
        $idSite = $archiveProcessor->getParams()->getSite()->getId();
        $maximumRows = GeneralConfig::getConfigValue('datatable_archiving_maximum_rows_events', $idSite);
        $maximumRowsSubtable = GeneralConfig::getConfigValue('datatable_archiving_maximum_rows_subtable_events', $idSite);

        $records = [
            // Event Category -> Event Actions
            Record::make(Record::TYPE_BLOB, Archiver::RECORD_EVENT_CATEGORY_ACTIONS),
            // Event Category -> Event Names
            Record::make(Record::TYPE_BLOB, Archiver::RECORD_EVENT_CATEGORY_NAMES),
            // Event Category -> Event Values
            Record::make(Record::TYPE_BLOB, Archiver::RECORD_EVENT_CATEGORY_VALUES),
            // Event Action -> Event Categories
            Record::make(Record::TYPE_BLOB, Archiver::RECORD_EVENT_ACTION_CATEGORIES),
            // Event Action -> Event Names
            Record::make(Record::TYPE_BLOB, Archiver::RECORD_EVENT_ACTION_NAMES),
            // Event Action -> Event Values
            Record::make(Record::TYPE_BLOB, Archiver::RECORD_EVENT_ACTION_VALUES),
            // Event Name -> Event Categories
            Record::make(Record::TYPE_BLOB, Archiver::RECORD_EVENT_NAME_CATEGORIES),
            // Event Name -> Event Actions
            Record::make(Record::TYPE_BLOB, Archiver::RECORD_EVENT_NAME_ACTIONS),
            // Event Name -> Event Values
            Record::make(Record::TYPE_BLOB, Archiver::RECORD_EVENT_NAME_VALUES),
        ];

        foreach ($records as $record) {
            $record->setMaxRowsInTable($maximumRows)
                ->setMaxRowsInSubtable($maximumRowsSubtable);
        }

        return $records;
    }

    protected function aggregate(ArchiveProcessor $archiveProcessor): array
    {
        $logAggregator = $archiveProcessor->getLogAggregator();

        // Initialize empty reports
        $reports = [
            Archiver::RECORD_EVENT_CATEGORY_ACTIONS => new DataTable(),
            Archiver::RECORD_EVENT_CATEGORY_NAMES => new DataTable(),
            Archiver::RECORD_EVENT_CATEGORY_VALUES => new DataTable(),
            Archiver::RECORD_EVENT_ACTION_CATEGORIES => new DataTable(),
            Archiver::RECORD_EVENT_ACTION_NAMES => new DataTable(),
            Archiver::RECORD_EVENT_ACTION_VALUES => new DataTable(),
            Archiver::RECORD_EVENT_NAME_CATEGORIES => new DataTable(),
            Archiver::RECORD_EVENT_NAME_ACTIONS => new DataTable(),
            Archiver::RECORD_EVENT_NAME_VALUES => new DataTable(),
        ];

        try {
            $this->aggregateDimensionReports($reports, $logAggregator);
            $this->aggregateValueReports($reports, $logAggregator);
        } catch (\Exception $e) {
            $logger = StaticContainer::get(LoggerInterface::class);
            $logger->error('EventsEnhanced: Failed to aggregate event dimension relations: {exception}', [
                'exception' => $e,
            ]);
        }

        return $reports;
    }

    /**
     * Build the shared FROM clause for both queries
     */
    protected function getFromClause(): array
    {
        return [
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
    }

    /**
     * Query 1: Aggregate the 6 dimension-to-dimension reports.
     *
     * Groups by category, action, name only (no custom_float).
     * Value metrics (sum, count with value) are computed as SQL aggregates.
     */
    protected function aggregateDimensionReports(array &$reports, LogAggregator $logAggregator): void
    {
        $select = "
            log_action_event_category.name as eventCategory,
            log_action_event_action.name as eventAction,
            log_action_event_name.name as eventName,
            count(distinct log_link_visit_action.idvisit) as `" . Metrics::INDEX_NB_VISITS . "`,
            count(*) as `" . Metrics::INDEX_EVENT_NB_HITS . "`,
            SUM(IF(log_link_visit_action.custom_float IS NOT NULL, 1, 0)) as `" . Metrics::INDEX_EVENT_NB_HITS_WITH_VALUE . "`,
            SUM(log_link_visit_action.custom_float) as `" . Metrics::INDEX_EVENT_SUM_EVENT_VALUE . "`
        ";

        $from = $this->getFromClause();

        $where = $logAggregator->getWhereStatement('log_link_visit_action', 'server_time');
        $where .= " AND log_link_visit_action.idaction_event_category IS NOT NULL";

        $groupBy = "log_link_visit_action.idaction_event_category,
                    log_link_visit_action.idaction_event_action,
                    log_link_visit_action.idaction_name";

        $orderBy = "`" . Metrics::INDEX_NB_VISITS . "` DESC";

        // Apply ranking query if configured
        $rankingQueryLimit = ArchivingHelper::getRankingQueryLimit();
        $rankingQuery = null;
        if ($rankingQueryLimit > 0) {
            $rankingQuery = new RankingQuery($rankingQueryLimit);
            $rankingQuery->addLabelColumn(['eventCategory', 'eventAction', 'eventName']);
            $rankingQuery->addColumn([Metrics::INDEX_EVENT_NB_HITS, Metrics::INDEX_NB_VISITS, Metrics::INDEX_EVENT_NB_HITS_WITH_VALUE], 'sum');
            $rankingQuery->addColumn(Metrics::INDEX_EVENT_SUM_EVENT_VALUE, 'sum');
        }

        $query = $logAggregator->generateQuery($select, $from, $where, $groupBy, $orderBy);

        if ($rankingQuery) {
            $query['sql'] = $rankingQuery->generateRankingQuery($query['sql']);
        }

        $resultSet = $logAggregator->getDb()->query($query['sql'], $query['bind']);

        if ($resultSet === false) {
            return;
        }

        while ($row = $resultSet->fetch()) {
            $this->aggregateDimensionRow($reports, $row);
        }
    }

    /**
     * Query 2: Aggregate the 3 value reports (Category->Values, Action->Values, Name->Values).
     *
     * Groups by category, action, name, AND custom_float to produce one row per
     * distinct event value so it can be used as a subtable label.
     */
    protected function aggregateValueReports(array &$reports, LogAggregator $logAggregator): void
    {
        $select = "
            log_action_event_category.name as eventCategory,
            log_action_event_action.name as eventAction,
            log_action_event_name.name as eventName,
            log_link_visit_action.custom_float as eventValue,
            count(distinct log_link_visit_action.idvisit) as `" . Metrics::INDEX_NB_VISITS . "`,
            count(*) as `" . Metrics::INDEX_EVENT_NB_HITS . "`,
            SUM(IF(log_link_visit_action.custom_float IS NOT NULL, 1, 0)) as `" . Metrics::INDEX_EVENT_NB_HITS_WITH_VALUE . "`,
            SUM(log_link_visit_action.custom_float) as `" . Metrics::INDEX_EVENT_SUM_EVENT_VALUE . "`
        ";

        $from = $this->getFromClause();

        $where = $logAggregator->getWhereStatement('log_link_visit_action', 'server_time');
        $where .= " AND log_link_visit_action.idaction_event_category IS NOT NULL";
        $where .= " AND log_link_visit_action.custom_float IS NOT NULL";

        $groupBy = "log_link_visit_action.idaction_event_category,
                    log_link_visit_action.idaction_event_action,
                    log_link_visit_action.idaction_name,
                    log_link_visit_action.custom_float";

        $orderBy = "`" . Metrics::INDEX_NB_VISITS . "` DESC";

        // Apply ranking query if configured
        $rankingQueryLimit = ArchivingHelper::getRankingQueryLimit();
        $rankingQuery = null;
        if ($rankingQueryLimit > 0) {
            $rankingQuery = new RankingQuery($rankingQueryLimit);
            $rankingQuery->addLabelColumn(['eventCategory', 'eventAction', 'eventName', 'eventValue']);
            $rankingQuery->addColumn([Metrics::INDEX_EVENT_NB_HITS, Metrics::INDEX_NB_VISITS, Metrics::INDEX_EVENT_NB_HITS_WITH_VALUE], 'sum');
            $rankingQuery->addColumn(Metrics::INDEX_EVENT_SUM_EVENT_VALUE, 'sum');
        }

        $query = $logAggregator->generateQuery($select, $from, $where, $groupBy, $orderBy);

        if ($rankingQuery) {
            $query['sql'] = $rankingQuery->generateRankingQuery($query['sql']);
        }

        $resultSet = $logAggregator->getDb()->query($query['sql'], $query['bind']);

        if ($resultSet === false) {
            return;
        }

        while ($row = $resultSet->fetch()) {
            $this->aggregateValueRow($reports, $row);
        }
    }

    /**
     * Aggregate a dimension row into the 6 dimension-to-dimension reports.
     * This does NOT handle value reports.
     */
    protected function aggregateDimensionRow(array &$reports, array $row): void
    {
        $columns = [
            Metrics::INDEX_NB_VISITS => $row[Metrics::INDEX_NB_VISITS] ?? 0,
            Metrics::INDEX_EVENT_NB_HITS => $row[Metrics::INDEX_EVENT_NB_HITS] ?? 0,
            Metrics::INDEX_EVENT_NB_HITS_WITH_VALUE => $row[Metrics::INDEX_EVENT_NB_HITS_WITH_VALUE] ?? 0,
            Metrics::INDEX_EVENT_SUM_EVENT_VALUE => $row[Metrics::INDEX_EVENT_SUM_EVENT_VALUE] ?? 0,
        ];

        $eventCategory = $row['eventCategory'] ?? '';
        $eventAction = $row['eventAction'] ?? '';
        $eventName = $row['eventName'] ?? '';

        // Event Category -> Event Actions
        if (!empty($eventCategory) && !empty($eventAction)) {
            $this->addToReport(
                $reports,
                Archiver::RECORD_EVENT_CATEGORY_ACTIONS,
                $eventCategory,
                $eventAction,
                $columns
            );
        }

        // Event Category -> Event Names
        if (!empty($eventCategory) && !empty($eventName)) {
            $this->addToReport(
                $reports,
                Archiver::RECORD_EVENT_CATEGORY_NAMES,
                $eventCategory,
                $eventName,
                $columns
            );
        }

        // Event Action -> Event Categories
        if (!empty($eventAction) && !empty($eventCategory)) {
            $this->addToReport(
                $reports,
                Archiver::RECORD_EVENT_ACTION_CATEGORIES,
                $eventAction,
                $eventCategory,
                $columns
            );
        }

        // Event Action -> Event Names
        if (!empty($eventAction) && !empty($eventName)) {
            $this->addToReport(
                $reports,
                Archiver::RECORD_EVENT_ACTION_NAMES,
                $eventAction,
                $eventName,
                $columns
            );
        }

        // Event Name -> Event Categories
        if (!empty($eventName) && !empty($eventCategory)) {
            $this->addToReport(
                $reports,
                Archiver::RECORD_EVENT_NAME_CATEGORIES,
                $eventName,
                $eventCategory,
                $columns
            );
        }

        // Event Name -> Event Actions
        if (!empty($eventName) && !empty($eventAction)) {
            $this->addToReport(
                $reports,
                Archiver::RECORD_EVENT_NAME_ACTIONS,
                $eventName,
                $eventAction,
                $columns
            );
        }
    }

    /**
     * Aggregate a value row into the 3 value reports.
     * Only handles Category->Values, Action->Values, Name->Values.
     */
    protected function aggregateValueRow(array &$reports, array $row): void
    {
        $columns = [
            Metrics::INDEX_NB_VISITS => $row[Metrics::INDEX_NB_VISITS] ?? 0,
            Metrics::INDEX_EVENT_NB_HITS => $row[Metrics::INDEX_EVENT_NB_HITS] ?? 0,
            Metrics::INDEX_EVENT_NB_HITS_WITH_VALUE => $row[Metrics::INDEX_EVENT_NB_HITS_WITH_VALUE] ?? 0,
            Metrics::INDEX_EVENT_SUM_EVENT_VALUE => $row[Metrics::INDEX_EVENT_SUM_EVENT_VALUE] ?? 0,
        ];

        $eventCategory = $row['eventCategory'] ?? '';
        $eventAction = $row['eventAction'] ?? '';
        $eventName = $row['eventName'] ?? '';
        // Convert event value to string label (e.g., "1.5", "100", etc.)
        $eventValue = isset($row['eventValue']) && $row['eventValue'] !== null
            ? (string) $row['eventValue']
            : '';

        // Event Category -> Event Values
        if (!empty($eventCategory) && !empty($eventValue)) {
            $this->addToReport(
                $reports,
                Archiver::RECORD_EVENT_CATEGORY_VALUES,
                $eventCategory,
                $eventValue,
                $columns
            );
        }

        // Event Action -> Event Values
        if (!empty($eventAction) && !empty($eventValue)) {
            $this->addToReport(
                $reports,
                Archiver::RECORD_EVENT_ACTION_VALUES,
                $eventAction,
                $eventValue,
                $columns
            );
        }

        // Event Name -> Event Values
        if (!empty($eventName) && !empty($eventValue)) {
            $this->addToReport(
                $reports,
                Archiver::RECORD_EVENT_NAME_VALUES,
                $eventName,
                $eventValue,
                $columns
            );
        }
    }

    /**
     * Add data to a report with main label and subtable label
     */
    protected function addToReport(array &$reports, string $recordName, string $mainLabel, string $subLabel, array $columns): void
    {
        if (empty($mainLabel) || empty($subLabel)) {
            return;
        }

        if (empty($reports[$recordName])) {
            $reports[$recordName] = new DataTable();
        }

        /** @var DataTable $table */
        $table = $reports[$recordName];

        $topLevelRow = $table->sumRowWithLabel($mainLabel, $columns);
        $topLevelRow->sumRowWithLabelToSubtable($subLabel, $columns);
    }
}
