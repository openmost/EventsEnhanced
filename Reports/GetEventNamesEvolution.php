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
use Piwik\Plugins\Events\Columns\EventName;
use Piwik\Report\ReportWidgetFactory;
use Piwik\Widget\WidgetsList;

/**
 * Evolution report for Event Names
 */
class GetEventNamesEvolution extends Report
{
    protected function init()
    {
        parent::init();

        $this->module = 'EventsEnhanced';
        $this->action = 'getEventNamesEvolution';
        $this->name = Piwik::translate('EventsEnhanced_EventNamesEvolution');
        $this->documentation = Piwik::translate('EventsEnhanced_EventNamesEvolutionDocumentation');

        $this->categoryId = 'General_Actions';
        $this->subcategoryId = 'Events_Events';

        $this->order = 1;

        // Set dimension for row evolution support
        $this->dimension = new EventName();

        // Exact 5 metrics in order: total events, visits, unique visitors, avg events per visit, sum event value
        $this->metrics = ['nb_events', 'nb_visits', 'nb_uniq_visitors', 'sum_event_value'];
    }

    public function configureWidgets(WidgetsList $widgetsList, ReportWidgetFactory $factory)
    {
        $widgetsList->addWidgetConfig(
            $factory->createWidget()
                ->setName('EventsEnhanced_EventNamesEvolution')
                ->forceViewDataTable(Evolution::ID)
                ->setAction('getEventNamesEvolutionGraph')
                ->setOrder(1)
        );
    }

    public function configureView(ViewDataTable $view)
    {
        $view->config->show_goals = false;
        $view->config->show_search = false;
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
            new GetEventCategoriesEvolution(),
            new GetEventActionsEvolution(),
        ];
    }
}
