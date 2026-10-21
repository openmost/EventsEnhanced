<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\EventsEnhanced\tests\Integration;

use Piwik\API\Request;
use Piwik\Plugins\EventsEnhanced\Tracker\RequestProcessor;
use Piwik\Tests\Framework\Fixture;
use Piwik\Tests\Framework\Mock\FakeAccess;
use Piwik\Tests\Framework\TestCase\IntegrationTestCase;
use Piwik\Tracker\Request as TrackerRequest;

/**
 * Tracks Media Analytics style events and checks the stored event names follow the GA4 names.
 *
 * @group EventsEnhanced
 * @group EventsEnhancedMediaEventNamesTest
 * @group Plugins
 */
class MediaEventNamesTest extends IntegrationTestCase
{
    public static function setUpBeforeClass(): void
    {
        // The video and form rows, and the Media Analytics renaming, need these plugins
        self::$fixture->extraPluginsToLoad[] = 'MediaAnalytics';
        self::$fixture->extraPluginsToLoad[] = 'FormAnalytics';

        parent::setUpBeforeClass();
    }

    private const DATE_TIME = '2025-01-06 09:00:00';

    protected $idSite;

    public function setUp(): void
    {
        parent::setUp();

        Fixture::createSuperUser();
        FakeAccess::clearAccess(true);
        $this->idSite = (int) Fixture::createWebsite('2024-12-01 00:00:00');

        $this->trackEvents([
            ['MediaVideo', 'play', 'Product demo'],
            ['MediaVideo', 'pause', 'Product demo'],
            ['MediaVideo', 'resume', 'Product demo'],
            ['MediaVideo', 'seek', 'Product demo'],
            ['MediaVideo', 'finish', 'Product demo'],
            ['MediaAudio', 'play', 'Podcast 12'],
            ['MediaAudio', 'finish', 'Podcast 12'],
            ['MediaVideo', 'unknown_action', 'Product demo'],
            ['Video', 'play', 'Product demo'],
        ]);
    }

    public function test_mediaEvents_areNamedAfterTheGa4Events(): void
    {
        $this->assertSame([
            'Product demo' => 2,
            'audio_complete' => 1,
            'audio_start' => 1,
            'video_complete' => 1,
            'video_pause' => 1,
            'video_resume' => 1,
            'video_seek' => 1,
            'video_start' => 1,
        ], $this->eventNames());
    }

    public function test_mediaEvents_keepTheirCategory_andGetACapitalizedAction(): void
    {
        $categories = $this->labelsToEvents('Events.getCategory');

        $this->assertSame(6, $categories['MediaVideo']);
        $this->assertSame(2, $categories['MediaAudio']);
        $this->assertSame(1, $categories['Video']);

        $actions = $this->labelsToEvents('Events.getAction');
        $this->assertSame(2, $actions['Play']);
        $this->assertSame(1, $actions['Pause']);
        $this->assertSame(1, $actions['Resume']);
        $this->assertSame(1, $actions['Seek']);
        $this->assertSame(2, $actions['Finish']);
        $this->assertSame(1, $actions['unknown_action']);
        $this->assertSame(1, $actions['play']);
    }

    public function test_mediaEvents_keepTheirName_whenMediaAnalyticsIsNotActivated(): void
    {
        $processor = new class extends RequestProcessor {
            protected function isMediaAnalyticsActivated(): bool
            {
                return false;
            }
        };
        $request = new TrackerRequest(['idsite' => $this->idSite, 'e_c' => 'MediaVideo', 'e_a' => 'play', 'e_n' => 'Product demo']);

        $processor->manipulateRequest($request);

        $this->assertSame('play', $request->getParam('e_a'));
        $this->assertSame('Product demo', $request->getParam('e_n'));
    }

    private function eventNames(): array
    {
        $names = $this->labelsToEvents('Events.getName');
        ksort($names);

        return $names;
    }

    private function labelsToEvents(string $method): array
    {
        $table = Request::processRequest($method, [
            'idSite' => $this->idSite,
            'period' => 'day',
            'date' => '2025-01-06',
            'filter_limit' => -1,
        ]);

        $counts = [];
        foreach ($table->getRows() as $row) {
            $counts[(string) $row->getColumn('label')] = (int) $row->getColumn('nb_events');
        }

        return $counts;
    }

    private function trackEvents(array $events): void
    {
        $tracker = Fixture::getTracker($this->idSite, self::DATE_TIME, true, true);
        $tracker->setVisitorId('a000000000000001');
        $tracker->setUrl('https://example.com/demo');

        foreach ($events as $i => [$category, $action, $name]) {
            $tracker->setForceVisitDateTime(date('Y-m-d H:i:s', strtotime(self::DATE_TIME) + 60 * $i));
            Fixture::checkResponse($tracker->doTrackEvent($category, $action, $name));
        }
    }
}
