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
use Piwik\Plugins\CoreVisualizations\Visualizations\JqplotGraph\Evolution;
use Piwik\Plugins\Events\Columns\EventCategory;

/**
 * Evolution report for Event Categories
 */
class GetEventCategoriesEvolution extends Report
{
    protected function init()
    {
        parent::init();

        $this->module = 'EventsEnhanced';
        $this->action = 'getEventCategoriesEvolution';
        $this->name = Piwik::translate('EventsEnhanced_EventCategoriesEvolution');
        $this->documentation = Piwik::translate('EventsEnhanced_EventCategoriesEvolutionDocumentation');

        // No category - only accessible via Related Reports
        $this->order = 2;

        // Set dimension for row evolution support
        $this->dimension = new EventCategory();

        // Exact 5 metrics in order: total events, visits, unique visitors, avg events per visit, sum event value
        $this->metrics = ['nb_events', 'nb_visits', 'nb_uniq_visitors', 'sum_event_value'];
    }

    // No widget - accessed via Related Reports

    public function configureView(ViewDataTable $view)
    {
        $view->config->show_goals = false;
        $view->config->show_search = false;

        // Limit to top 10 event categories to prevent graph overload
        $view->requestConfig->filter_sort_column = 'nb_events';
        $view->requestConfig->filter_sort_order = 'desc';
        $view->requestConfig->filter_limit = 10;
    }

    public function getDefaultTypeViewDataTable()
    {
        return Evolution::ID;
    }

    /**
     * @return Report[]
     */
    public function getRelatedReports()
    {
        return [
            new GetEventNamesEvolution(),
            new GetEventActionsEvolution(),
        ];
    }
}
