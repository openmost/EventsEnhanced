<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\EventsEnhanced;

use Piwik\Common;
use Piwik\Piwik;
use Piwik\Plugins\CustomDimensions\API as CustomDimensionsAPI;
use Piwik\Plugins\CustomDimensions\CustomDimensions;
use Piwik\ViewDataTable\Factory as ViewDataTableFactory;

/**
 * Controller for EventsEnhanced plugin
 * Uses plugin's own archived data via API methods
 */
class Controller extends \Piwik\Plugin\Controller
{

    /**
     * Render the Event Detail page
     * Vue component handles all report loading via WidgetLoader
     */
    public function getEventDetail()
    {
        $this->checkSitePermission();

        // Default to 'name' dimension when accessing via menu (shows top event names)
        $dimensionType = Common::getRequestVar('eventDimensionType', 'name', 'string');
        if (!in_array($dimensionType, ['category', 'action', 'name'])) {
            $dimensionType = 'name';
        }

        $dimensionValue = Common::getRequestVar('eventDimensionValue', '', 'string');
        $dimensionValue = Common::unsanitizeInputValue($dimensionValue);

        // Fetch dimension values for selector
        $dimensionValues = $this->fetchDimensionValues($dimensionType);

        // If no dimension value selected, use first available
        if (empty($dimensionValue) && !empty($dimensionValues)) {
            $dimensionValue = $dimensionValues[0]['key'];
        }

        // Get custom dimensions for this site
        $customDimensions = $this->getActiveCustomDimensions();

        return $this->renderTemplate('eventDetails', [
            'selectedDimensionType' => $dimensionType,
            'selectedDimensionValue' => $dimensionValue,
            'dimensionValues' => $dimensionValues,
            'customDimensions' => $customDimensions,
        ]);
    }

    /**
     * Get active custom dimensions for the current site
     */
    private function getActiveCustomDimensions()
    {
        $customDimensions = [];
        try {
            $api = CustomDimensionsAPI::getInstance();
            $dimensions = $api->getConfiguredCustomDimensionsHavingScope(
                $this->idSite,
                CustomDimensions::SCOPE_ACTION
            );
            foreach ($dimensions as $dim) {
                if (!empty($dim['active'])) {
                    $customDimensions[] = [
                        'id' => (int) $dim['idcustomdimension'],
                        'name' => $dim['name'],
                    ];
                }
            }
            // Sort by dimension ID ASC
            usort($customDimensions, function ($a, $b) {
                return $a['id'] - $b['id'];
            });
        } catch (\Exception $e) {
            // CustomDimensions plugin might not be available
        }
        return $customDimensions;
    }

    /**
     * Fetch dimension values for the selector
     */
    private function fetchDimensionValues($dimensionType)
    {
        $apiMethodMap = [
            'category' => 'Events.getCategory',
            'action' => 'Events.getAction',
            'name' => 'Events.getName',
        ];
        $apiMethod = $apiMethodMap[$dimensionType] ?? 'Events.getCategory';

        try {
            $report = \Piwik\API\Request::processRequest($apiMethod, [
                'idSite' => $this->idSite,
                'period' => Common::getRequestVar('period', 'day', 'string'),
                'date' => Common::getRequestVar('date', 'today', 'string'),
                'filter_limit' => 500,
                'filter_sort_order' => 'desc',
                'filter_sort_column' => 'nb_events',
                'showColumns' => 'label,nb_events',
            ]);

            $values = [];
            foreach ($report->getRows() as $row) {
                $label = $row->getColumn('label');
                $nbEvents = $row->getColumn('nb_events');
                if ($label && $label !== '-') {
                    $values[] = [
                        'key' => $label,
                        'value' => $label . ' (' . $nbEvents . ')',
                    ];
                }
            }
            return $values;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Controller action for Page URLs report - uses plugin's archived data
     */
    public function getPageUrlsForEvent()
    {
        $this->checkSitePermission();

        $dimensionType = Common::getRequestVar('eventDimensionType', 'category', 'string');
        if (!in_array($dimensionType, ['category', 'action', 'name'])) {
            $dimensionType = 'category';
        }
        $dimensionValue = $this->getDimensionValueFromRequest($dimensionType);

        $view = ViewDataTableFactory::build(
            'table',
            'EventsEnhanced.getPageUrlsForEvent',
            'EventsEnhanced.getPageUrlsForEvent'
        );

        $view->requestConfig->request_parameters_to_modify['dimensionType'] = $dimensionType;
        $view->requestConfig->request_parameters_to_modify['dimensionValue'] = $dimensionValue;

        $view->config->title = Piwik::translate('Actions_PageUrls');
        $this->configureEventDimensionTable($view);

        return $this->renderView($view);
    }

    /**
     * Controller action for Page Titles report - uses plugin's archived data
     */
    public function getPageTitlesForEvent()
    {
        $this->checkSitePermission();

        $dimensionType = Common::getRequestVar('eventDimensionType', 'category', 'string');
        if (!in_array($dimensionType, ['category', 'action', 'name'])) {
            $dimensionType = 'category';
        }
        $dimensionValue = $this->getDimensionValueFromRequest($dimensionType);

        $view = ViewDataTableFactory::build(
            'table',
            'EventsEnhanced.getPageTitlesForEvent',
            'EventsEnhanced.getPageTitlesForEvent'
        );

        $view->requestConfig->request_parameters_to_modify['dimensionType'] = $dimensionType;
        $view->requestConfig->request_parameters_to_modify['dimensionValue'] = $dimensionValue;

        $view->config->title = Piwik::translate('Actions_WidgetPageTitles');
        $this->configureEventDimensionTable($view);

        return $this->renderView($view);
    }

    /**
     * Controller action for Countries report - uses plugin's archived data
     */
    public function getCountriesForEvent()
    {
        $this->checkSitePermission();

        $dimensionType = Common::getRequestVar('eventDimensionType', 'category', 'string');
        if (!in_array($dimensionType, ['category', 'action', 'name'])) {
            $dimensionType = 'category';
        }
        $dimensionValue = $this->getDimensionValueFromRequest($dimensionType);

        $view = ViewDataTableFactory::build(
            'table',
            'EventsEnhanced.getCountriesForEvent',
            'EventsEnhanced.getCountriesForEvent'
        );

        $view->requestConfig->request_parameters_to_modify['dimensionType'] = $dimensionType;
        $view->requestConfig->request_parameters_to_modify['dimensionValue'] = $dimensionValue;

        $view->config->title = Piwik::translate('UserCountry_Country');
        $this->configureEventDimensionTable($view);

        return $this->renderView($view);
    }

    /**
     * Controller action for related Event Actions (for category dimension)
     */
    public function getEventActionsForCategory()
    {
        $this->checkSitePermission();

        $eventCategory = $this->getDimensionValueFromRequest('category');

        $view = ViewDataTableFactory::build(
            'table',
            'EventsEnhanced.getEventActionsForCategory',
            'EventsEnhanced.getEventActionsForCategory'
        );

        $view->requestConfig->request_parameters_to_modify['eventCategory'] = $eventCategory;

        $view->config->title = Piwik::translate('Events_EventActions');
        $this->configureEventDimensionTable($view);

        return $this->renderView($view);
    }

    /**
     * Controller action for related Event Names (for category dimension)
     */
    public function getEventNamesForCategory()
    {
        $this->checkSitePermission();

        $eventCategory = $this->getDimensionValueFromRequest('category');

        $view = ViewDataTableFactory::build(
            'table',
            'EventsEnhanced.getEventNamesForCategory',
            'EventsEnhanced.getEventNamesForCategory'
        );

        $view->requestConfig->request_parameters_to_modify['eventCategory'] = $eventCategory;

        $view->config->title = Piwik::translate('Events_EventNames');
        $this->configureEventDimensionTable($view);

        return $this->renderView($view);
    }

    /**
     * Controller action for related Event Categories (for action dimension)
     */
    public function getEventCategoriesForAction()
    {
        $this->checkSitePermission();

        $eventAction = $this->getDimensionValueFromRequest('action');

        $view = ViewDataTableFactory::build(
            'table',
            'EventsEnhanced.getEventCategoriesForAction',
            'EventsEnhanced.getEventCategoriesForAction'
        );

        $view->requestConfig->request_parameters_to_modify['eventAction'] = $eventAction;

        $view->config->title = Piwik::translate('Events_EventCategories');
        $this->configureEventDimensionTable($view);

        return $this->renderView($view);
    }

    /**
     * Controller action for related Event Names (for action dimension)
     */
    public function getEventNamesForAction()
    {
        $this->checkSitePermission();

        $eventAction = $this->getDimensionValueFromRequest('action');

        $view = ViewDataTableFactory::build(
            'table',
            'EventsEnhanced.getEventNamesForAction',
            'EventsEnhanced.getEventNamesForAction'
        );

        $view->requestConfig->request_parameters_to_modify['eventAction'] = $eventAction;

        $view->config->title = Piwik::translate('Events_EventNames');
        $this->configureEventDimensionTable($view);

        return $this->renderView($view);
    }

    /**
     * Controller action for related Event Categories (for name dimension)
     */
    public function getEventCategoriesForName()
    {
        $this->checkSitePermission();

        $eventName = $this->getDimensionValueFromRequest('name');

        $view = ViewDataTableFactory::build(
            'table',
            'EventsEnhanced.getEventCategoriesForName',
            'EventsEnhanced.getEventCategoriesForName'
        );

        $view->requestConfig->request_parameters_to_modify['eventName'] = $eventName;

        $view->config->title = Piwik::translate('Events_EventCategories');
        $this->configureEventDimensionTable($view);

        return $this->renderView($view);
    }

    /**
     * Controller action for related Event Actions (for name dimension)
     */
    public function getEventActionsForName()
    {
        $this->checkSitePermission();

        $eventName = $this->getDimensionValueFromRequest('name');

        $view = ViewDataTableFactory::build(
            'table',
            'EventsEnhanced.getEventActionsForName',
            'EventsEnhanced.getEventActionsForName'
        );

        $view->requestConfig->request_parameters_to_modify['eventName'] = $eventName;

        $view->config->title = Piwik::translate('Events_EventActions');
        $this->configureEventDimensionTable($view);

        return $this->renderView($view);
    }

    /**
     * Controller action for Event Values (for category dimension)
     */
    public function getEventValuesForCategory()
    {
        $this->checkSitePermission();

        $eventCategory = $this->getDimensionValueFromRequest('category');

        $view = ViewDataTableFactory::build(
            'table',
            'EventsEnhanced.getEventValuesForCategory',
            'EventsEnhanced.getEventValuesForCategory'
        );

        $view->requestConfig->request_parameters_to_modify['eventCategory'] = $eventCategory;

        $view->config->title = Piwik::translate('Events_EventValue');
        $this->configureEventDimensionTable($view);

        return $this->renderView($view);
    }

    /**
     * Controller action for Event Values (for action dimension)
     */
    public function getEventValuesForAction()
    {
        $this->checkSitePermission();

        $eventAction = $this->getDimensionValueFromRequest('action');

        $view = ViewDataTableFactory::build(
            'table',
            'EventsEnhanced.getEventValuesForAction',
            'EventsEnhanced.getEventValuesForAction'
        );

        $view->requestConfig->request_parameters_to_modify['eventAction'] = $eventAction;

        $view->config->title = Piwik::translate('Events_EventValue');
        $this->configureEventDimensionTable($view);

        return $this->renderView($view);
    }

    /**
     * Controller action for Event Values (for name dimension)
     */
    public function getEventValuesForName()
    {
        $this->checkSitePermission();

        $eventName = $this->getDimensionValueFromRequest('name');

        $view = ViewDataTableFactory::build(
            'table',
            'EventsEnhanced.getEventValuesForName',
            'EventsEnhanced.getEventValuesForName'
        );

        $view->requestConfig->request_parameters_to_modify['eventName'] = $eventName;

        $view->config->title = Piwik::translate('Events_EventValue');
        $this->configureEventDimensionTable($view);

        return $this->renderView($view);
    }

    /**
     * Get evolution graph for event metrics
     */
    public function getEvolutionGraph()
    {
        $this->checkSitePermission();

        $dimensionType = Common::getRequestVar('eventDimensionType', 'category', 'string');
        if (!in_array($dimensionType, ['category', 'action', 'name'])) {
            $dimensionType = 'category';
        }
        $dimensionValue = $this->getDimensionValueFromRequest($dimensionType);
        $apiMethod = $this->getEventsApiMethod($dimensionType);

        $columns = Common::getRequestVar('columns', false);
        if (false !== $columns) {
            $columns = Piwik::getArrayFromApiParameter($columns);
        }

        $view = $this->getLastUnitGraph(
            'EventsEnhanced',
            __FUNCTION__,
            $apiMethod
        );

        // Use label parameter to filter by specific dimension value
        if (!empty($dimensionValue)) {
            $view->requestConfig->request_parameters_to_modify['label'] = $dimensionValue;
        }

        $selectableColumns = ['nb_visits', 'nb_events', 'sum_event_value', 'nb_events_with_value'];
        $view->config->selectable_columns = $selectableColumns;

        // Use requested columns or default to nb_events
        if (!empty($columns)) {
            $view->config->columns_to_display = $columns;
        } else {
            $view->config->columns_to_display = ['nb_events'];
        }

        // Use same translation keys as native Events plugin
        $view->config->addTranslation('nb_events', Piwik::translate('Events_Events'));
        $view->config->addTranslation('nb_visits', Piwik::translate('General_ColumnNbVisits'));
        $view->config->addTranslation('sum_event_value', Piwik::translate('Events_EventValue'));
        $view->config->addTranslation('nb_events_with_value', Piwik::translate('Events_EventsWithValue'));
        $view->config->addTranslation('min_event_value', Piwik::translate('Events_MinValue'));
        $view->config->addTranslation('max_event_value', Piwik::translate('Events_MaxValue'));
        $view->config->addTranslation('avg_event_value', Piwik::translate('Events_AvgValue'));

        // Remove the ProfessionalServices promo footer injected by native Events report
        $view->config->filters[] = function () use ($view) {
            $view->config->show_footer_message = '';
        };

        return $this->renderView($view);
    }

    /**
     * Get dimension value from URL parameters
     */
    private function getDimensionValueFromRequest($dimensionType)
    {
        // First try the new param name
        $value = Common::getRequestVar('eventDimensionValue', '', 'string');

        // Fallback to legacy param names
        if (empty($value)) {
            $paramMap = [
                'category' => 'eventCategory',
                'action' => 'eventAction',
                'name' => 'eventName',
            ];
            $paramName = $paramMap[$dimensionType] ?? 'eventCategory';
            $value = Common::getRequestVar($paramName, '', 'string');
        }

        return Common::unsanitizeInputValue($value);
    }

    /**
     * Get Events API method for dimension type
     */
    private function getEventsApiMethod($dimensionType)
    {
        $methodMap = [
            'category' => 'Events.getCategory',
            'action' => 'Events.getAction',
            'name' => 'Events.getName',
        ];

        return $methodMap[$dimensionType] ?? 'Events.getCategory';
    }

    /**
     * AJAX endpoint to get dimension values for a specific dimension type
     * Returns JSON with dimension values
     */
    public function getDimensionValues()
    {
        $this->checkSitePermission();

        $dimensionType = Common::getRequestVar('dimensionType', 'category', 'string');
        if (!in_array($dimensionType, ['category', 'action', 'name'])) {
            $dimensionType = 'category';
        }

        $values = $this->fetchDimensionValues($dimensionType);

        return json_encode($values);
    }

    /**
     * Render the Event Names evolution graph
     * Shows top event names over time
     */
    public function getEventNamesEvolutionGraph()
    {
        $this->checkSitePermission();

        $view = $this->getLastUnitGraph(
            'EventsEnhanced',
            __FUNCTION__,
            'EventsEnhanced.getEventNamesEvolution'
        );

        $this->configureEvolutionGraph($view);

        return $this->renderView($view);
    }

    /**
     * Render the Event Categories evolution graph
     * Shows top event categories over time
     */
    public function getEventCategoriesEvolutionGraph()
    {
        $this->checkSitePermission();

        $view = $this->getLastUnitGraph(
            'EventsEnhanced',
            __FUNCTION__,
            'EventsEnhanced.getEventCategoriesEvolution'
        );

        $this->configureEvolutionGraph($view);

        return $this->renderView($view);
    }

    /**
     * Render the Event Actions evolution graph
     * Shows top event actions over time
     */
    public function getEventActionsEvolutionGraph()
    {
        $this->checkSitePermission();

        $view = $this->getLastUnitGraph(
            'EventsEnhanced',
            __FUNCTION__,
            'EventsEnhanced.getEventActionsEvolution'
        );

        $this->configureEvolutionGraph($view);

        return $this->renderView($view);
    }

    /**
     * Configure common settings for evolution graph views
     * Metrics in order: total events, visits, unique visitors, avg events per visit, sum event value
     */
    private function configureEvolutionGraph($view)
    {
        // Limit the number of series to prevent browser overload with high cardinality
        $view->requestConfig->filter_sort_column = 'nb_events';
        $view->requestConfig->filter_sort_order = 'desc';
        $view->requestConfig->filter_limit = 10;

        // Configure metrics to display: 5 metrics in specific order
        $selectableColumns = ['nb_events', 'nb_visits', 'nb_uniq_visitors', 'sum_event_value'];
        $view->config->selectable_columns = $selectableColumns;

        // Check if columns parameter was passed in the request (user changed metric selection)
        $columns = Common::getRequestVar('columns', false);
        if (false !== $columns) {
            $columns = Piwik::getArrayFromApiParameter($columns);
        }

        // Use requested columns or default to nb_events
        if (!empty($columns)) {
            $view->config->columns_to_display = $columns;
        } else {
            $view->config->columns_to_display = ['nb_events'];
        }

        // Add translations
        $view->config->addTranslation('nb_events', Piwik::translate('Events_TotalEvents'));
        $view->config->addTranslation('nb_visits', Piwik::translate('General_ColumnNbVisits'));
        $view->config->addTranslation('nb_uniq_visitors', Piwik::translate('General_ColumnNbUniqVisitors'));
        $view->config->addTranslation('sum_event_value', Piwik::translate('Events_TotalValue'));

        $view->config->show_goals = false;
    }

    /**
     * Configure table view for dimension-to-dimension reports (has nb_events)
     * Used for: Category→Actions, Category→Names, Action→Categories, etc.
     * Available metrics: nb_events, nb_visits
     */
    private function configureEventDimensionTable($view)
    {
        // Disable "display table with engagement metrics" option
        $view->config->show_table_all_columns = false;

        // Set default columns: Total Events and Visits only
        $view->config->columns_to_display = ['label', 'nb_events', 'nb_visits'];

        // Enable selectable columns for bar/pie chart visualizations
        $view->config->selectable_columns = ['nb_events', 'nb_visits'];

        // Add translations
        $view->config->addTranslation('nb_events', Piwik::translate('Events_TotalEvents'));
        $view->config->addTranslation('nb_visits', Piwik::translate('General_ColumnNbVisits'));

        // Show totals row option in UI (only for visualizations that support it)
        if (property_exists($view->config, 'show_totals_row')) {
            $view->config->show_totals_row = true;
        }

        // Sort by nb_events by default
        $view->requestConfig->filter_sort_column = 'nb_events';
        $view->requestConfig->filter_sort_order = 'desc';

        // Enable visualization switching
        $view->config->show_all_views_icons = true;
        $view->config->show_bar_chart = true;
        $view->config->show_pie_chart = true;

        // Disable goals
        $view->config->show_goals = false;
    }

}
