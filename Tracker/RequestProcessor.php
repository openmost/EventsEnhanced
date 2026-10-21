<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\EventsEnhanced\Tracker;

use Piwik\Plugin\Manager;
use Piwik\Tracker;
use Piwik\Tracker\Request;

/**
 * Renames the automatic events of the Media Analytics plugin after the GA4 event names.
 *
 * Media Analytics sends one event per player interaction with the media title as event name, so the
 * Event Names report lists titles instead of interactions. The event name becomes the GA4 name
 * (video_start, video_complete...) and the action is capitalized (Play, Finish...). The category is kept:
 * Media Analytics relies on it to recognise its own events. The media title stays available in the Media reports. Nothing changes when Media
 * Analytics is not activated.
 */
class RequestProcessor extends Tracker\RequestProcessor
{
    private const EVENT_NAMES = [
        'MediaVideo' => [
            'play' => 'video_start',
            'resume' => 'video_resume',
            'pause' => 'video_pause',
            'seek' => 'video_seek',
            'finish' => 'video_complete',
        ],
        'MediaAudio' => [
            'play' => 'audio_start',
            'resume' => 'audio_resume',
            'pause' => 'audio_pause',
            'seek' => 'audio_seek',
            'finish' => 'audio_complete',
        ],
    ];

    public function manipulateRequest(Request $request)
    {
        $category = $request->getParam('e_c');
        $action = $request->getParam('e_a');

        if (!is_string($category) || !is_string($action) || !isset(self::EVENT_NAMES[$category][$action])) {
            return;
        }

        // Without Media Analytics these categories come from the site's own tracking and keep their names.
        if (!$this->isMediaAnalyticsActivated()) {
            return;
        }

        $request->setParam('e_a', ucfirst($action));
        $request->setParam('e_n', self::EVENT_NAMES[$category][$action]);
    }

    protected function isMediaAnalyticsActivated(): bool
    {
        return Manager::getInstance()->isPluginActivated('MediaAnalytics');
    }
}
