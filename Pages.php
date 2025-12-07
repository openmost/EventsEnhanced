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
use Piwik\Plugins\CoreVisualizations\Visualizations\JqplotGraph\Evolution;
use Piwik\Report\ReportWidgetFactory;

/**
 * Pages helper for EventsEnhanced plugin
 * Follows standard Matomo widget configuration patterns (similar to Goals/Pages.php)
 */
class Pages
{
    private $factory;
    private $dimensionType;
    private $dimensionValue;
    private $paramName;

    public function __construct(ReportWidgetFactory $reportFactory)
    {
        $this->factory = $reportFactory;
        $this->loadDimensionFromUrl();
    }

    /**
     * Load dimension parameters from URL
     */
    private function loadDimensionFromUrl()
    {
        $this->dimensionType = Common::getRequestVar('eventDimensionType', 'name', 'string');
        $this->dimensionValue = Common::getRequestVar('eventDimensionValue', '', 'string');

        $paramMap = [
            'category' => 'eventCategory',
            'action' => 'eventAction',
            'name' => 'eventName',
        ];
        $this->paramName = $paramMap[$this->dimensionType] ?? 'eventCategory';

        // Try legacy parameter names
        if (empty($this->dimensionValue)) {
            $this->dimensionValue = Common::getRequestVar($this->paramName, '', 'string');
        }
    }

    /**
     * Create the event detail page widgets
     *
     * @return array Widget configurations
     */
    public function createEventDetailPage()
    {
        $subcategory = 'EventsEnhanced_EventsDetails';
        $widgets = [];

        $params = [
            'dimensionType' => $this->dimensionType,
            'dimensionValue' => $this->dimensionValue,
            $this->paramName => $this->dimensionValue,
        ];

        // 1. Evolution Graph widget
        $evolutionWidget = $this->factory->createWidget();
        $evolutionWidget->setSubcategoryId($subcategory);
        $evolutionWidget->setModule('EventsEnhanced');
        $evolutionWidget->setAction('getEvolutionGraph');
        $evolutionWidget->setName('');
        $evolutionWidget->setOrder(5);
        $evolutionWidget->forceViewDataTable(Evolution::ID);
        $evolutionWidget->setIsNotWidgetizable();
        $evolutionWidget->setParameters($params);
        $widgets[] = $evolutionWidget;

        // 2. Related dimension reports based on current dimension type
        if ($this->dimensionType === 'category') {
            // For category: show Actions and Names
            $widgets[] = $this->createReportWidget($subcategory, 'getEventActionsForCategory', 'Events_EventActions', 10, ['eventCategory' => $this->dimensionValue]);
            $widgets[] = $this->createReportWidget($subcategory, 'getEventNamesForCategory', 'Events_EventNames', 15, ['eventCategory' => $this->dimensionValue]);
        } elseif ($this->dimensionType === 'action') {
            // For action: show Categories and Names
            $widgets[] = $this->createReportWidget($subcategory, 'getEventCategoriesForAction', 'Events_EventCategories', 10, ['eventAction' => $this->dimensionValue]);
            $widgets[] = $this->createReportWidget($subcategory, 'getEventNamesForAction', 'Events_EventNames', 15, ['eventAction' => $this->dimensionValue]);
        } else {
            // For name: show Categories and Actions
            $widgets[] = $this->createReportWidget($subcategory, 'getEventCategoriesForName', 'Events_EventCategories', 10, ['eventName' => $this->dimensionValue]);
            $widgets[] = $this->createReportWidget($subcategory, 'getEventActionsForName', 'Events_EventActions', 15, ['eventName' => $this->dimensionValue]);
        }

        // 3. Page URLs widget
        $widgets[] = $this->createReportWidget($subcategory, 'getPageUrlsForEvent', 'Actions_SubmenuPageUrls', 20, $params);

        // 4. Page Titles widget
        $widgets[] = $this->createReportWidget($subcategory, 'getPageTitlesForEvent', 'Actions_SubmenuPageTitles', 25, $params);

        return $widgets;
    }

    /**
     * Create a report widget config
     */
    private function createReportWidget($subcategory, $action, $name, $order, $params)
    {
        $widget = $this->factory->createWidget();
        $widget->setSubcategoryId($subcategory);
        $widget->setModule('EventsEnhanced');
        $widget->setAction($action);
        $widget->setName($name);
        $widget->setOrder($order);
        $widget->setIsNotWidgetizable();
        $widget->setParameters($params);
        return $widget;
    }
}
