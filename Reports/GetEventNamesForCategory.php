<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\EventsEnhanced\Reports;

use Piwik\Common;
use Piwik\Piwik;
use Piwik\Plugin\Report;
use Piwik\Plugin\ViewDataTable;
use Piwik\Plugins\CoreVisualizations\Visualizations\HtmlTable;
use Piwik\Plugins\Events\Columns\EventName;

/**
 * Report for event names within a specific category
 */
class GetEventNamesForCategory extends Report
{
    protected function init()
    {
        parent::init();

        $this->module = 'EventsEnhanced';
        $this->action = 'getEventNamesForCategory';
        $this->name = Piwik::translate('Events_EventNames');
        $this->documentation = Piwik::translate('EventsEnhanced_EventNamesForCategoryDocumentation');
        $this->dimension = new EventName();
        $this->order = 111;

        // Don't show in any menu - this report is only used in event detail pages
        $this->isSubtableReport = true;

        // Define metrics
        $this->metrics = ['nb_visits', 'nb_events', 'nb_events_with_value', 'sum_event_value'];
        $this->processedMetrics = [];

        // Define parameters that need to be passed to the API
        $this->parameters = [
            'eventCategory' => Common::getRequestVar('eventCategory', '', 'string'),
        ];
    }

    public function configureView(ViewDataTable $view)
    {
        $view->config->show_search = true;
        $view->config->show_exclude_low_population = false;
        $view->config->show_totals_row = true;
        $view->config->addTranslation('label', Piwik::translate('Events_EventName'));
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
