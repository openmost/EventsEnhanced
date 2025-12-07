<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\EventsEnhanced;

/**
 * EventsEnhanced plugin - Enhances Matomo native events reports with detailed dimension pages
 *
 * This plugin adds detail pages for each event dimension value (category, action, name).
 * Each detail page shows:
 * - Evolution graph for the dimension value
 * - Reports for related dimensions
 * - Page URLs and titles where events were triggered
 * - Custom dimension reports (if available)
 */
class EventsEnhanced extends \Piwik\Plugin
{
    /**
     * Register plugin events/hooks
     *
     * @return array
     */
    public function registerEvents()
    {
        return [
            'AssetManager.getJavaScriptFiles' => 'getJsFiles',
            'AssetManager.getStylesheetFiles' => 'getStylesheetFiles',
            'Translate.getClientSideTranslationKeys' => 'getClientSideTranslationKeys',
            'Url.getQueryParametersToExclude' => 'getQueryParametersToExclude',
        ];
    }

    /**
     * Exclude our custom parameters from being added to menu links
     * This prevents the eventCategory/eventAction/eventName params from polluting all menu URLs
     *
     * @param array $parametersToExclude
     */
    public function getQueryParametersToExclude(&$parametersToExclude)
    {
        $parametersToExclude[] = 'eventDimensionType';
        $parametersToExclude[] = 'eventDimensionValue';
    }

    /**
     * Register JavaScript files
     *
     * @param array $jsFiles
     */
    public function getJsFiles(&$jsFiles)
    {
        $jsFiles[] = 'plugins/EventsEnhanced/javascripts/eventsEnhancedRowAction.js';
    }

    /**
     * Register stylesheet files
     *
     * @param array $stylesheets
     */
    public function getStylesheetFiles(&$stylesheets)
    {
        $stylesheets[] = 'plugins/EventsEnhanced/stylesheets/eventsEnhanced.less';
    }

    /**
     * Register client-side translation keys
     *
     * @param array $translationKeys
     */
    public function getClientSideTranslationKeys(&$translationKeys)
    {
        // EventsEnhanced translation keys
        $translationKeys[] = 'EventsEnhanced_EventsDetails';
        $translationKeys[] = 'EventsEnhanced_EventDimension';
        $translationKeys[] = 'EventsEnhanced_EventsOverTime';
        $translationKeys[] = 'EventsEnhanced_EventDetailsRowActionTitle';
        $translationKeys[] = 'EventsEnhanced_EventDetailsRowActionDescription';

        // Core Events translation keys (used in dimension selectors and reports)
        $translationKeys[] = 'Events_EventCategory';
        $translationKeys[] = 'Events_EventAction';
        $translationKeys[] = 'Events_EventName';
        $translationKeys[] = 'Events_EventValue';
        $translationKeys[] = 'Events_EventCategories';
        $translationKeys[] = 'Events_EventActions';
        $translationKeys[] = 'Events_EventNames';

        $translationKeys[] = 'Actions_PageUrls';
        $translationKeys[] = 'UserCountry_Country';

        // Premium translation keys
        $translationKeys[] = 'EventsEnhanced_PremiumLinkText';
        $translationKeys[] = 'EventsEnhanced_PremiumText';
    }
}
