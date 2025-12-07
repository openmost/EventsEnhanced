<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\EventsEnhanced\Categories;

use Piwik\Category\Subcategory;

class EventDetailSubcategory extends Subcategory
{
    protected $categoryId = 'General_Actions';
    protected $id = 'EventsEnhanced_EventsDetails';
    protected $order = 41;
}
