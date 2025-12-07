<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\EventsEnhanced;

use Piwik\Archive;
use Piwik\Common;
use Piwik\DataTable;
use Piwik\Metrics;
use Piwik\Piwik;
use Piwik\Plugins\CustomDimensions\API as CustomDimensionsAPI;
use Piwik\Plugins\CustomDimensions\CustomDimensions;

/**
 * API for EventsEnhanced plugin
 *
 * Provides methods for fetching event data filtered by dimension values.
 *
 * @method static API getInstance()
 */
class API extends \Piwik\Plugin\API
{
    /**
     * Get event actions for a specific category value
     * Uses archived data from RecordBuilder for action-level filtering
     *
     * @param int $idSite
     * @param string $period
     * @param string $date
     * @param string $eventCategory The event category value to filter by
     * @param bool|string $segment
     * @return DataTable
     */
    public function getEventActionsForCategory($idSite, $period, $date, $eventCategory, $segment = false)
    {
        Piwik::checkUserHasViewAccess($idSite);

        $eventCategory = Common::unsanitizeInputValue($eventCategory);

        try {
            // Use createDataTableFromArchive with expanded=true to load subtables
            $dataTable = Archive::createDataTableFromArchive(
                Archiver::RECORD_EVENT_CATEGORY_ACTIONS,
                $idSite,
                $period,
                $date,
                $segment,
                $expanded = true
            );

            // Filter to the specific category and extract its subtable (actions)
            $this->filterAndFlattenToSubtable($dataTable, $eventCategory);

            $dataTable->queueFilter('ReplaceColumnNames');
            return $dataTable;
        } catch (\Exception $e) {
            return new DataTable();
        }
    }

    /**
     * Get event names for a specific category value
     * Uses archived data from RecordBuilder for action-level filtering
     *
     * @param int $idSite
     * @param string $period
     * @param string $date
     * @param string $eventCategory The event category value to filter by
     * @param bool|string $segment
     * @return DataTable
     */
    public function getEventNamesForCategory($idSite, $period, $date, $eventCategory, $segment = false)
    {
        Piwik::checkUserHasViewAccess($idSite);

        $eventCategory = Common::unsanitizeInputValue($eventCategory);

        try {
            $dataTable = Archive::createDataTableFromArchive(
                Archiver::RECORD_EVENT_CATEGORY_NAMES,
                $idSite,
                $period,
                $date,
                $segment,
                $expanded = true
            );

            $this->filterAndFlattenToSubtable($dataTable, $eventCategory);

            $dataTable->queueFilter('ReplaceColumnNames');
            return $dataTable;
        } catch (\Exception $e) {
            return new DataTable();
        }
    }

    /**
     * Get event categories for a specific action value
     * Uses archived data from RecordBuilder for action-level filtering
     *
     * @param int $idSite
     * @param string $period
     * @param string $date
     * @param string $eventAction The event action value to filter by
     * @param bool|string $segment
     * @return DataTable
     */
    public function getEventCategoriesForAction($idSite, $period, $date, $eventAction, $segment = false)
    {
        Piwik::checkUserHasViewAccess($idSite);

        $eventAction = Common::unsanitizeInputValue($eventAction);

        try {
            $dataTable = Archive::createDataTableFromArchive(
                Archiver::RECORD_EVENT_ACTION_CATEGORIES,
                $idSite,
                $period,
                $date,
                $segment,
                $expanded = true
            );

            $this->filterAndFlattenToSubtable($dataTable, $eventAction);

            $dataTable->queueFilter('ReplaceColumnNames');
            return $dataTable;
        } catch (\Exception $e) {
            return new DataTable();
        }
    }

    /**
     * Get event names for a specific action value
     * Uses archived data from RecordBuilder for action-level filtering
     *
     * @param int $idSite
     * @param string $period
     * @param string $date
     * @param string $eventAction The event action value to filter by
     * @param bool|string $segment
     * @return DataTable
     */
    public function getEventNamesForAction($idSite, $period, $date, $eventAction, $segment = false)
    {
        Piwik::checkUserHasViewAccess($idSite);

        $eventAction = Common::unsanitizeInputValue($eventAction);

        try {
            $dataTable = Archive::createDataTableFromArchive(
                Archiver::RECORD_EVENT_ACTION_NAMES,
                $idSite,
                $period,
                $date,
                $segment,
                $expanded = true
            );

            $this->filterAndFlattenToSubtable($dataTable, $eventAction);

            $dataTable->queueFilter('ReplaceColumnNames');
            return $dataTable;
        } catch (\Exception $e) {
            return new DataTable();
        }
    }

    /**
     * Get event categories for a specific name value
     * Uses archived data from RecordBuilder for action-level filtering
     *
     * @param int $idSite
     * @param string $period
     * @param string $date
     * @param string $eventName The event name value to filter by
     * @param bool|string $segment
     * @return DataTable
     */
    public function getEventCategoriesForName($idSite, $period, $date, $eventName, $segment = false)
    {
        Piwik::checkUserHasViewAccess($idSite);

        $eventName = Common::unsanitizeInputValue($eventName);

        try {
            $dataTable = Archive::createDataTableFromArchive(
                Archiver::RECORD_EVENT_NAME_CATEGORIES,
                $idSite,
                $period,
                $date,
                $segment,
                $expanded = true
            );

            $this->filterAndFlattenToSubtable($dataTable, $eventName);

            $dataTable->queueFilter('ReplaceColumnNames');
            return $dataTable;
        } catch (\Exception $e) {
            return new DataTable();
        }
    }

    /**
     * Get event actions for a specific name value
     * Uses archived data from RecordBuilder for action-level filtering
     *
     * @param int $idSite
     * @param string $period
     * @param string $date
     * @param string $eventName The event name value to filter by
     * @param bool|string $segment
     * @return DataTable
     */
    public function getEventActionsForName($idSite, $period, $date, $eventName, $segment = false)
    {
        Piwik::checkUserHasViewAccess($idSite);

        $eventName = Common::unsanitizeInputValue($eventName);

        try {
            $dataTable = Archive::createDataTableFromArchive(
                Archiver::RECORD_EVENT_NAME_ACTIONS,
                $idSite,
                $period,
                $date,
                $segment,
                $expanded = true
            );

            $this->filterAndFlattenToSubtable($dataTable, $eventName);

            $dataTable->queueFilter('ReplaceColumnNames');
            return $dataTable;
        } catch (\Exception $e) {
            return new DataTable();
        }
    }

    /**
     * Get event values for a specific category
     * Uses archived data from RecordBuilder for action-level filtering
     *
     * @param int $idSite
     * @param string $period
     * @param string $date
     * @param string $eventCategory The event category value to filter by
     * @param bool|string $segment
     * @return DataTable
     */
    public function getEventValuesForCategory($idSite, $period, $date, $eventCategory, $segment = false)
    {
        Piwik::checkUserHasViewAccess($idSite);

        $eventCategory = Common::unsanitizeInputValue($eventCategory);

        try {
            $dataTable = Archive::createDataTableFromArchive(
                Archiver::RECORD_EVENT_CATEGORY_VALUES,
                $idSite,
                $period,
                $date,
                $segment,
                $expanded = true
            );

            $this->filterAndFlattenToSubtable($dataTable, $eventCategory);

            $dataTable->queueFilter('ReplaceColumnNames');
            return $dataTable;
        } catch (\Exception $e) {
            return new DataTable();
        }
    }

    /**
     * Get event values for a specific action
     * Uses archived data from RecordBuilder for action-level filtering
     *
     * @param int $idSite
     * @param string $period
     * @param string $date
     * @param string $eventAction The event action value to filter by
     * @param bool|string $segment
     * @return DataTable
     */
    public function getEventValuesForAction($idSite, $period, $date, $eventAction, $segment = false)
    {
        Piwik::checkUserHasViewAccess($idSite);

        $eventAction = Common::unsanitizeInputValue($eventAction);

        try {
            $dataTable = Archive::createDataTableFromArchive(
                Archiver::RECORD_EVENT_ACTION_VALUES,
                $idSite,
                $period,
                $date,
                $segment,
                $expanded = true
            );

            $this->filterAndFlattenToSubtable($dataTable, $eventAction);

            $dataTable->queueFilter('ReplaceColumnNames');
            return $dataTable;
        } catch (\Exception $e) {
            return new DataTable();
        }
    }

    /**
     * Get event values for a specific name
     * Uses archived data from RecordBuilder for action-level filtering
     *
     * @param int $idSite
     * @param string $period
     * @param string $date
     * @param string $eventName The event name value to filter by
     * @param bool|string $segment
     * @return DataTable
     */
    public function getEventValuesForName($idSite, $period, $date, $eventName, $segment = false)
    {
        Piwik::checkUserHasViewAccess($idSite);

        $eventName = Common::unsanitizeInputValue($eventName);

        try {
            $dataTable = Archive::createDataTableFromArchive(
                Archiver::RECORD_EVENT_NAME_VALUES,
                $idSite,
                $period,
                $date,
                $segment,
                $expanded = true
            );

            $this->filterAndFlattenToSubtable($dataTable, $eventName);

            $dataTable->queueFilter('ReplaceColumnNames');
            return $dataTable;
        } catch (\Exception $e) {
            return new DataTable();
        }
    }

    /**
     * Filter to specific label and flatten its subtable to be the main table
     * Works with both DataTable and DataTable\Map
     */
    private function filterAndFlattenToSubtable($dataTable, string $label): void
    {
        if ($dataTable instanceof DataTable\Map) {
            $dataTable->filter(function (DataTable $table) use ($label) {
                $this->extractSubtableByLabel($table, $label);
            });
        } else {
            $this->extractSubtableByLabel($dataTable, $label);
        }
    }

    /**
     * Extract subtable from a row matching the given label
     */
    private function extractSubtableByLabel(DataTable $table, string $label): void
    {
        $row = $table->getRowFromLabel($label);

        if ($row) {
            $subtable = $row->getSubtable();
            if ($subtable) {
                // Clear the table and add rows from subtable
                $table->deleteRowsOffset(0);
                foreach ($subtable->getRows() as $subRow) {
                    $table->addRow($subRow);
                }
                return;
            }
        }

        // No matching row or subtable found, return empty table
        $table->deleteRowsOffset(0);
    }

    /**
     * Filter for nested subtables (event dimension -> custom dimension id -> values)
     * Works with both DataTable and DataTable\Map
     */
    private function filterAndFlattenNestedSubtable($dataTable, string $eventLabel, string $dimensionLabel): void
    {
        if ($dataTable instanceof DataTable\Map) {
            $dataTable->filter(function (DataTable $table) use ($eventLabel, $dimensionLabel) {
                $this->extractNestedSubtableByLabel($table, $eventLabel, $dimensionLabel);
            });
        } else {
            $this->extractNestedSubtableByLabel($dataTable, $eventLabel, $dimensionLabel);
        }
    }

    /**
     * Extract nested subtable by navigating two levels
     */
    private function extractNestedSubtableByLabel(DataTable $table, string $eventLabel, string $dimensionLabel): void
    {
        $row = $table->getRowFromLabel($eventLabel);

        if ($row) {
            $dimensionTable = $row->getSubtable();
            if ($dimensionTable) {
                $dimensionRow = $dimensionTable->getRowFromLabel($dimensionLabel);
                if ($dimensionRow) {
                    $valueTable = $dimensionRow->getSubtable();
                    if ($valueTable) {
                        $table->deleteRowsOffset(0);
                        foreach ($valueTable->getRows() as $subRow) {
                            $table->addRow($subRow);
                        }
                        return;
                    }
                }
            }
        }

        // No matching row found, return empty table
        $table->deleteRowsOffset(0);
    }


    /**
     * Get available custom dimensions for a site that have action scope
     *
     * @param int $idSite
     * @return array
     */
    public function getAvailableCustomDimensions($idSite)
    {
        Piwik::checkUserHasViewAccess($idSite);

        try {
            $api = CustomDimensionsAPI::getInstance();
            $dimensions = $api->getConfiguredCustomDimensionsHavingScope($idSite, CustomDimensions::SCOPE_ACTION);

            // Filter only active dimensions
            return array_filter($dimensions, function ($dim) {
                return !empty($dim['active']);
            });
        } catch (\Exception $e) {
            // CustomDimensions plugin might not be available
            return [];
        }
    }

    /**
     * Get events with 3-level hierarchy: Category -> Action -> Name
     * Uses custom archived data with proper flat/expanded/idSubtable support
     *
     * @param int $idSite
     * @param string $period
     * @param string $date
     * @param bool|string $segment
     * @param bool $expanded Whether to include subtables
     * @param bool $flat Whether to flatten the table
     * @param int|bool $idSubtable Subtable ID for drilling down
     * @return DataTable
     */
    public function getEventsWithAllDimensions($idSite, $period, $date, $segment = false, $expanded = false, $flat = false, $idSubtable = false)
    {
        Piwik::checkUserHasViewAccess($idSite);

        // Use custom 3-level archived data: Category -> Action -> Name
        $dataTable = \Piwik\Archive::createDataTableFromArchive(
            Archiver::RECORD_EVENTS_ALL_DIMENSIONS,
            $idSite,
            $period,
            $date,
            $segment,
            $expanded,
            $flat,
            $idSubtable
        );

        // Set column aggregation ops for proper metric handling
        // - min/max for event value metrics
        // - skip for dimension string columns (added by Flattener when show_dimensions=1)
        //   These columns are added after the API returns but before tables are merged,
        //   so we must set skip ops here to prevent "adding two strings" warnings
        $dataTable->filter(function (DataTable $table) {
            $table->setMetadata(DataTable::COLUMN_AGGREGATION_OPS_METADATA_NAME, [
                // Metric aggregation ops (using both index and name for before/after ReplaceColumnNames)
                Metrics::INDEX_EVENT_MIN_EVENT_VALUE => 'min',
                Metrics::INDEX_EVENT_MAX_EVENT_VALUE => 'max',
                'min_event_value' => 'min',
                'max_event_value' => 'max',
                // Skip dimension string columns when aggregating (prevents summing strings during merge)
                'Events_EventCategory' => 'skip',
                'Events_EventAction' => 'skip',
                'Events_EventName' => 'skip',
            ]);
        });

        // Handle "not set" labels
        if ($flat) {
            $dataTable->filterSubtables('Piwik\Plugins\Events\DataTable\Filter\ReplaceEventNameNotSet');
        } else {
            $dataTable->filter('AddSegmentValue', array(function ($label) {
                if ($label === \Piwik\Plugins\Events\Archiver::EVENT_NAME_NOT_SET) {
                    return false;
                }
                return $label;
            }));
        }

        $dataTable->filter('Piwik\Plugins\Events\DataTable\Filter\ReplaceEventNameNotSet');
        $dataTable->queueFilter('ReplaceColumnNames');

        return $dataTable;
    }

    /**
     * Get event names evolution data
     * Proxies to Events.getName for evolution graph
     *
     * @param int $idSite
     * @param string $period
     * @param string $date
     * @param bool|string $segment
     * @return DataTable
     */
    public function getEventNamesEvolution($idSite, $period, $date, $segment = false)
    {
        Piwik::checkUserHasViewAccess($idSite);

        $dataTable = \Piwik\API\Request::processRequest('Events.getName', [
            'idSite' => $idSite,
            'period' => $period,
            'date' => $date,
            'segment' => $segment,
            'format' => 'original',
        ]);

        return $dataTable;
    }

    /**
     * Get event categories evolution data
     * Proxies to Events.getCategory for evolution graph
     *
     * @param int $idSite
     * @param string $period
     * @param string $date
     * @param bool|string $segment
     * @return DataTable
     */
    public function getEventCategoriesEvolution($idSite, $period, $date, $segment = false)
    {
        Piwik::checkUserHasViewAccess($idSite);

        $dataTable = \Piwik\API\Request::processRequest('Events.getCategory', [
            'idSite' => $idSite,
            'period' => $period,
            'date' => $date,
            'segment' => $segment,
            'format' => 'original',
        ]);

        return $dataTable;
    }

    /**
     * Get event actions evolution data
     * Proxies to Events.getAction for evolution graph
     *
     * @param int $idSite
     * @param string $period
     * @param string $date
     * @param bool|string $segment
     * @return DataTable
     */
    public function getEventActionsEvolution($idSite, $period, $date, $segment = false)
    {
        Piwik::checkUserHasViewAccess($idSite);

        $dataTable = \Piwik\API\Request::processRequest('Events.getAction', [
            'idSite' => $idSite,
            'period' => $period,
            'date' => $date,
            'segment' => $segment,
            'format' => 'original',
        ]);

        return $dataTable;
    }
}