<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\EventsEnhanced\Reports;

use Piwik\Columns\Dimension;
use Piwik\Common;
use Piwik\Piwik;
use Piwik\Plugin\ViewDataTable;
use Piwik\Plugins\Events\Columns\EventAction;
use Piwik\Plugins\Events\Columns\EventCategory;
use Piwik\Plugins\Events\Columns\EventName;
use Piwik\Plugins\Events\Columns\Metrics\AverageEventValue;
use Piwik\Plugins\Events\Reports\Base as EventsBase;
use Piwik\Report\ReportWidgetFactory;
use Piwik\Widget\WidgetsList;

/**
 * Events report showing 3-level hierarchy: Category -> Action -> Name
 * Extends Events Base to get the same metrics and behavior as native Events reports
 */
class GetEventsWithAllDimensions extends EventsBase
{
    protected function init()
    {
        parent::init();

        $this->module = 'EventsEnhanced';
        $this->action = 'getEventsWithAllDimensions';
        $this->name = Piwik::translate('EventsEnhanced_EventsWithAllDimensions');
        $this->documentation = Piwik::translate('EventsEnhanced_EventsWithAllDimensionsDocumentation');

        // Use Event Category as primary dimension
        $this->dimension = new EventCategory();

        // Metrics in order: Visits, Unique visitors, Events, Event value, Average value, Min, Max
        $this->metrics = ['nb_visits', 'nb_uniq_visitors', 'nb_events', 'sum_event_value', 'min_event_value', 'max_event_value'];

        $this->processedMetrics = [
            new AverageEventValue(),
        ];

        // Order 2 to appear after native events (order 0)
        $this->order = 2;

        // Enable subtable loading for hierarchical view
        $this->actionToLoadSubTables = $this->action;
    }

    public function configureWidgets(WidgetsList $widgetsList, ReportWidgetFactory $factory)
    {
        $widgetsList->addWidgetConfig(
            $factory->createWidget()
                ->setName('EventsEnhanced_EventsWithAllDimensions')
                ->setAction('getEventsWithAllDimensions')
                ->setOrder(2)
        );
    }

    /**
     * Get the second level dimension (EventAction)
     */
    public function getSubtableDimension()
    {
        return new EventAction();
    }

    /**
     * Get the third level dimension (EventName)
     */
    public function getThirdLevelTableDimension()
    {
        return new EventName();
    }

    /**
     * Get the dimension for the nth level subtable (as used by Flattener)
     * The Flattener passes $level as the current recursion depth.
     * When at level N, it's processing rows and needs the dimension for the subtable.
     * So level 2 = we're at Action level, need Name dimension for the subtable
     */
    public function getNthLevelTableDimension(int $level): ?Dimension
    {
        // Flattener logic:
        // - Level 1: uses getDimension() -> Category, getSubtableDimension() -> Action
        // - Level 2: processing Actions, needs dimension for Names subtable
        switch ($level) {
            case 2:
                // At level 2 (Action rows), the subtable dimension is Name
                return new EventName();
            default:
                return null;
        }
    }

    protected function configureFooterMessage(ViewDataTable $view)
    {
        // Do not show the ProfessionalServices promo footer from Events Base
    }

    public function configureView(ViewDataTable $view)
    {
        // Call parent to get native Events behavior (selectable_columns, etc.)
        parent::configureView($view);

        // Disable engagement metrics - this is event scope, not visit scope
        $view->config->show_goals = false;
        $view->config->show_ecommerce = false;
        $view->config->show_pivot_by_subtable = false;
        $view->config->show_table_all_columns = false;
        $view->config->show_exclude_low_population = false;

        // Set columns to display in exact order
        $view->config->columns_to_display = [
            'label',
            'nb_visits',
            'nb_uniq_visitors',
            'nb_events',
            'sum_event_value',
            'avg_event_value',
            'min_event_value',
            'max_event_value',
        ];

        // Add translations for dimension columns (added by Flattener when show_dimensions=1)
        $view->config->addTranslation('Events_EventCategory', Piwik::translate('Events_EventCategory'));
        $view->config->addTranslation('Events_EventAction', Piwik::translate('Events_EventAction'));
        $view->config->addTranslation('Events_EventName', Piwik::translate('Events_EventName'));

        // Add translations for metric columns
        $view->config->addTranslation('sum_event_value', Piwik::translate('Events_TotalValue'));
        $view->config->addTranslation('nb_events', Piwik::translate('Events_TotalEvents'));
        $view->config->addTranslation('nb_visits', Piwik::translate('General_ColumnNbVisits'));
        $view->config->addTranslation('avg_event_value', Piwik::translate('Events_AvgValue'));

        // Enable search and flatten table options
        $view->config->show_search = true;
        $view->config->show_flatten_table = true;

        // Default to flat view with separate dimension columns (if not explicitly set in URL)
        if (Common::getRequestVar('flat', '', 'string') === '') {
            $view->requestConfig->flat = 1;
            $view->config->custom_parameters['flat'] = 1;
        }
        if (Common::getRequestVar('show_dimensions', '', 'string') === '') {
            $view->requestConfig->show_dimensions = 1;
            $view->config->custom_parameters['show_dimensions'] = 1;
        }

        // Show totals row option in UI (only for visualizations that support it)
        if (property_exists($view->config, 'show_totals_row')) {
            $view->config->show_totals_row = true;
        }

        // Enable visualization switching
        $view->config->show_all_views_icons = true;
        $view->config->show_bar_chart = true;
        $view->config->show_pie_chart = true;

        // Default sort by total events (descending)
        $view->requestConfig->filter_sort_column = 'nb_events';
        $view->requestConfig->filter_sort_order = 'desc';
    }
}
