<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\EventsEnhanced\Reports;

use Piwik\Piwik;
use Piwik\Plugin\Report;
use Piwik\Plugin\ViewDataTable;
use Piwik\Plugins\CoreVisualizations\Visualizations\HtmlTable;
use Piwik\Plugins\Events\Columns\EventCategory;

/**
 * Report for event categories containing a specific event name
 */
class GetEventCategoriesForName extends Report
{
    protected function init()
    {
        parent::init();

        $this->module = 'EventsEnhanced';
        $this->action = 'getEventCategoriesForName';
        $this->name = Piwik::translate('Events_EventCategories');
        $this->documentation = Piwik::translate('EventsEnhanced_EventCategoriesForNameDocumentation');
        $this->dimension = new EventCategory();
        $this->order = 114;

        // Define metrics
        $this->metrics = ['nb_visits', 'nb_events', 'nb_events_with_value', 'sum_event_value'];
        $this->processedMetrics = [];

        $this->parameters = ['eventName' => ''];
    }

    public function configureView(ViewDataTable $view)
    {
        $view->config->show_search = true;
        $view->config->show_exclude_low_population = false;
        if (property_exists($view->config, 'show_totals_row')) {
            $view->config->show_totals_row = true;
        }
        $view->config->addTranslation('label', Piwik::translate('Events_EventCategory'));
        $view->config->addTranslation('nb_visits', Piwik::translate('General_ColumnNbVisits'));
        $view->config->addTranslation('nb_events', Piwik::translate('Events_NbEvents'));

        // Enable visualization switching
        $view->config->show_all_views_icons = true;
        $view->config->show_bar_chart = true;
        $view->config->show_pie_chart = true;
        $view->config->selectable_columns = ['nb_events', 'nb_visits'];

        if ($view->isViewDataTableId(HtmlTable::ID)) {
            $view->config->disable_row_evolution = false;
        }
    }

    public function getDefaultSortColumn()
    {
        return 'nb_events';
    }
}
