<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\EventsEnhanced;

use Piwik\Archive\ArchiveInvalidator;
use Piwik\Plugin\Manager;
use Piwik\Updater;
use Piwik\Updates as PiwikUpdates;

/**
 * Builds the new Events derived from actions report for past periods, within the
 * [General] rearchive_reports_in_past_last_n_months setting, so it is not empty after the update.
 */
class Updates_6_1_0 extends PiwikUpdates
{
    /**
     * @var ArchiveInvalidator
     */
    private $invalidator;

    public function __construct(ArchiveInvalidator $invalidator)
    {
        $this->invalidator = $invalidator;
    }

    public function doUpdate(Updater $updater)
    {
        if (Manager::getInstance()->isPluginActivated('EventsEnhanced')) {
            $this->invalidator->scheduleReArchiving('all', 'EventsEnhanced', Archiver::RECORD_EVENTS_DERIVED_FROM_ACTIONS);
        }
    }
}
