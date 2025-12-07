<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\EventsEnhanced;

/**
 * Archiver for EventsEnhanced plugin
 * Contains record name constants used by RecordBuilders
 */
class Archiver extends \Piwik\Plugin\Archiver
{
    // Dimension-to-dimension relationships (action-level)
    // Event Category -> Event Actions
    const RECORD_EVENT_CATEGORY_ACTIONS = 'EventsEnhanced_EventCategoryActions';
    // Event Category -> Event Names
    const RECORD_EVENT_CATEGORY_NAMES = 'EventsEnhanced_EventCategoryNames';
    // Event Action -> Event Categories
    const RECORD_EVENT_ACTION_CATEGORIES = 'EventsEnhanced_EventActionCategories';
    // Event Action -> Event Names
    const RECORD_EVENT_ACTION_NAMES = 'EventsEnhanced_EventActionNames';
    // Event Name -> Event Categories
    const RECORD_EVENT_NAME_CATEGORIES = 'EventsEnhanced_EventNameCategories';
    // Event Name -> Event Actions
    const RECORD_EVENT_NAME_ACTIONS = 'EventsEnhanced_EventNameActions';

    // 3-level hierarchy: Category -> Action -> Name
    const RECORD_EVENTS_ALL_DIMENSIONS = 'EventsEnhanced_EventsAllDimensions';

    // Event Value relations (value as dimension label)
    // Event Category -> Event Values
    const RECORD_EVENT_CATEGORY_VALUES = 'EventsEnhanced_EventCategoryValues';
    // Event Action -> Event Values
    const RECORD_EVENT_ACTION_VALUES = 'EventsEnhanced_EventActionValues';
    // Event Name -> Event Values
    const RECORD_EVENT_NAME_VALUES = 'EventsEnhanced_EventNameValues';
}
