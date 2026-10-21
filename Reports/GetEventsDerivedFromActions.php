<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\EventsEnhanced\Reports;

use Piwik\DataTable;
use Piwik\Piwik;
use Piwik\Plugin\Report;
use Piwik\Plugin\ViewDataTable;
use Piwik\Plugins\Events\Columns\EventName;
use Piwik\Plugins\EventsEnhanced\RecordBuilders\EventsDerivedFromActions;
use Piwik\Report\ReportWidgetFactory;
use Piwik\Widget\WidgetsList;

/**
 * The events GA4 collects automatically, rebuilt from Matomo data. Shown below Events (All Dimensions), which it
 * complements without mixing: these rows are not Matomo events.
 */
class GetEventsDerivedFromActions extends Report
{
    private const COLUMN_COUNTED_FROM = 'counted_from';

    private const SOURCE_TRANSLATIONS = [
        EventsDerivedFromActions::SOURCE_VISITS => 'General_ColumnNbVisits',
        EventsDerivedFromActions::SOURCE_NEW_VISITS => 'General_NewVisits',
        EventsDerivedFromActions::SOURCE_PAGEVIEWS => 'General_ColumnPageviews',
        EventsDerivedFromActions::SOURCE_OUTLINKS => 'General_Outlinks',
        EventsDerivedFromActions::SOURCE_DOWNLOADS => 'General_Downloads',
        EventsDerivedFromActions::SOURCE_SITE_SEARCH => 'Actions_ColumnSearches',
        EventsDerivedFromActions::SOURCE_VIDEO_PLAYS => 'MediaAnalytics_ColumnTotalVideoPlays',
        EventsDerivedFromActions::SOURCE_VIDEO_PROGRESS => 'MediaAnalytics_ColumnTotalVideoPlays',
        EventsDerivedFromActions::SOURCE_VIDEO_COMPLETE => 'MediaAnalytics_ColumnTotalVideoPlays',
        EventsDerivedFromActions::SOURCE_FORM_STARTS => 'FormAnalytics_ColumnFormStarts',
        EventsDerivedFromActions::SOURCE_FORM_SUBMISSIONS => 'FormAnalytics_ColumnFormSubmissions',
    ];

    protected function init()
    {
        $this->categoryId = 'General_Actions';
        $this->subcategoryId = 'Events_Events';
        $this->module = 'EventsEnhanced';
        $this->action = 'getEventsDerivedFromActions';
        $this->name = Piwik::translate('EventsEnhanced_EventsDerivedFromActions');
        $this->documentation = Piwik::translate('EventsEnhanced_EventsDerivedFromActionsDocumentation');
        $this->dimension = new EventName();
        $this->metrics = ['nb_events', 'nb_visits'];
        $this->order = 3;
    }

    public function configureWidgets(WidgetsList $widgetsList, ReportWidgetFactory $factory)
    {
        $widgetsList->addWidgetConfig(
            $factory->createWidget()
                ->setName('EventsEnhanced_EventsDerivedFromActions')
                ->setAction('getEventsDerivedFromActions')
                ->setIsWide()
                ->setOrder(3)
        );
    }

    public function configureView(ViewDataTable $view)
    {
        $view->config->show_goals = false;
        $view->config->show_ecommerce = false;
        $view->config->show_table_all_columns = false;
        $view->config->show_exclude_low_population = false;
        $view->config->show_search = false;
        $view->config->columns_to_display = ['label', 'nb_events', 'nb_visits', self::COLUMN_COUNTED_FROM];

        $view->config->addTranslation('label', Piwik::translate('Events_EventName'));
        $view->config->addTranslation('nb_events', Piwik::translate('Events_TotalEvents'));
        $view->config->addTranslation('nb_visits', Piwik::translate('General_ColumnNbVisits'));
        $view->config->addTranslation(self::COLUMN_COUNTED_FROM, Piwik::translate('EventsEnhanced_CountedFrom'));

        if (property_exists($view->config, 'show_totals_row')) {
            $view->config->show_totals_row = false;
        }

        $view->requestConfig->filter_sort_column = 'nb_events';
        $view->requestConfig->filter_sort_order = 'desc';

        $view->config->filters[] = function (DataTable $table) {
            foreach ($table->getRows() as $row) {
                $source = $row->getMetadata(EventsDerivedFromActions::METADATA_SOURCE);
                if (isset(self::SOURCE_TRANSLATIONS[$source])) {
                    $row->setColumn(self::COLUMN_COUNTED_FROM, Piwik::translate(self::SOURCE_TRANSLATIONS[$source]));
                }
            }
        };
    }
}
