<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\EventsEnhanced;

use Piwik\Archive\ArchiveInvalidator;
use Piwik\Container\StaticContainer;

/**
 * EventsEnhanced plugin - Enhances Matomo native events reports with detailed dimension pages
 *
 * This plugin adds detail pages for each event dimension value (category, action, name).
 * Each detail page shows:
 * - Evolution graph for the dimension value
 * - Reports for related dimensions
 * - Previews of the Premium reports (page URLs, countries, Custom Dimensions)
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
            'Template.afterEventsReport' => 'renderOpenmostCommunicationAfterEvents',
            'Widget.filterWidgets' => 'addOpenmostCommunicationWidgets',
            'Template.beforeContent' => 'renderOpenmostCommunication',
            'AssetManager.getJavaScriptFiles' => 'getJsFiles',
            'AssetManager.getStylesheetFiles' => 'getStylesheetFiles',
            'Translate.getClientSideTranslationKeys' => 'getClientSideTranslationKeys',
            'Url.getQueryParametersToExclude' => 'getQueryParametersToExclude',
        ];
    }

    public const PREMIUM_PLUGIN = 'EventsEnhancedPremium';

    /**
     * EventsEnhancedPremium is an add-on of this plugin: when it is activated, its reports replace the previews
     */
    public static function isPremiumActivated(): bool
    {
        return \Piwik\Plugin\Manager::getInstance()->isPluginActivated(self::PREMIUM_PLUGIN);
    }

    /**
     * Rebuilds the reports of past periods from the raw logs, so a site that switches between the free and the
     * Premium plugin, or installs one of them, gets its history back without running any command. The period
     * is limited by the [General] rearchive_reports_in_past_last_n_months setting.
     */
    public function activate()
    {
        $this->schedulePluginReArchiving();
    }

    public function deactivate()
    {
        StaticContainer::get(ArchiveInvalidator::class)->removeInvalidationsSafely('all', $this->getPluginName());
    }

    /**
     * Loads the plugin in tracker mode for Tracker\RequestProcessor, which renames the Media Analytics events
     *
     * @return bool
     */
    public function isTrackerPlugin()
    {
        return true;
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
        $translationKeys[] = 'EventsEnhanced_DimensionNoData';
        $translationKeys[] = 'EventsEnhanced_DimensionsNoData';
        $translationKeys[] = 'CoreHome_ThereIsNoDataForThisReport';
        $translationKeys[] = 'General_And';

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

        // CustomDimensions translation key (for fallback display)
        $translationKeys[] = 'CustomDimensions_CustomDimensionId';

        // Premium translation keys
        $translationKeys[] = 'EventsEnhanced_PremiumLinkText';
        $translationKeys[] = 'EventsEnhanced_PremiumText';
    }

    public function renderOpenmostCommunication(&$out, $layout, $module = '', $action = '')
    {
        OpenmostCommunication::beforeContent($out, (string) $layout, (string) $module, (string) $action, $this->getPluginName());
    }

    public function addOpenmostCommunicationWidgets($list)
    {
        OpenmostCommunication::filterWidgets($list, $this->getPluginName());
    }

    public function renderOpenmostCommunicationAfterEvents(&$out, $dataTable = null)
    {
        OpenmostCommunication::afterEventsReport($out, $this->getPluginName());
    }
}
