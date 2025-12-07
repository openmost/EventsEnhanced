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

/**
 * Report metadata for the Event Detail page
 * The actual page is rendered by the EventDetails widget using Vue
 */
class GetEventDetail extends Report
{
    protected function init()
    {
        parent::init();

        $this->module = 'EventsEnhanced';
        $this->action = 'getEventDetail';
        // Use General_Actions (Behaviour) category with own subcategory
        // to appear as separate menu item under Events in left menu
        $this->categoryId = 'General_Actions';
        $this->subcategoryId = 'EventsEnhanced_EventsDetails';
        $this->name = Piwik::translate('EventsEnhanced_EventsDetails');
        $this->documentation = Piwik::translate('EventsEnhanced_EventDetailDocumentation');
        // Order 41 to appear just after Events (which is order 40)
        $this->order = 41;
    }
}
