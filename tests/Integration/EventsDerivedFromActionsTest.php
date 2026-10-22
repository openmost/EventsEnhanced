<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\EventsEnhanced\tests\Integration;

use Piwik\API\Request;
use Piwik\Common;
use Piwik\Db;
use Piwik\Plugin\Manager;
use Piwik\Tests\Framework\Fixture;
use Piwik\Tests\Framework\Mock\FakeAccess;
use Piwik\Tests\Framework\TestCase\IntegrationTestCase;

/**
 * @group EventsEnhanced
 * @group EventsEnhancedEventsDerivedFromActionsTest
 * @group Plugins
 */
class EventsDerivedFromActionsTest extends IntegrationTestCase
{
    public static function setUpBeforeClass(): void
    {
        // The video and form rows, and the Media Analytics renaming, need these plugins
        self::$fixture->extraPluginsToLoad[] = 'MediaAnalytics';
        self::$fixture->extraPluginsToLoad[] = 'FormAnalytics';

        parent::setUpBeforeClass();
    }

    protected const PLUGIN = 'EventsEnhanced';

    protected $idSite;

    public function setUp(): void
    {
        parent::setUp();

        Fixture::createSuperUser();
        FakeAccess::clearAccess(true);
        $this->idSite = (int) Fixture::createWebsite('2024-12-01 00:00:00');

        // 2025-01-06: a new visitor, then the same visitor back for a second visit
        $tracker = $this->tracker('a000000000000001', '2025-01-06 09:00:00');
        $tracker->doTrackPageView('Home');
        $this->atTime($tracker, '2025-01-06 09:01:00')->doTrackAction('https://partner.example.org/', 'link');
        $this->atTime($tracker, '2025-01-06 09:02:00')->doTrackAction('https://example.com/guide.pdf', 'download');
        $this->atTime($tracker, '2025-01-06 09:03:00')->doTrackSiteSearch('pricing');
        $this->atTime($tracker, '2025-01-06 09:04:00')->doTrackEvent('File', 'Download', 'file_download');
        $this->atTime($tracker, '2025-01-06 09:05:00')->doTrackEvent('Form', 'Submit', 'form_submit');

        $tracker = $this->tracker('a000000000000001', '2025-01-06 15:00:00');
        $tracker->doTrackPageView('Home');
        $this->atTime($tracker, '2025-01-06 15:01:00')->doTrackPageView('Pricing');

        // 2025-01-07: another new visitor
        $tracker = $this->tracker('b000000000000002', '2025-01-07 10:00:00');
        $tracker->doTrackPageView('Home');
        $this->atTime($tracker, '2025-01-07 10:01:00')->doTrackAction('https://example.com/guide.pdf', 'download');
    }

    public function test_onlyEventsDerivedFromMatomoData_areListed(): void
    {
        $this->assertSame([
            'click' => ['outlinks', 1, 1],
            'file_download' => ['downloads', 1, 1],
            'first_visit' => ['new_visits', 1, 1],
            'page_view' => ['pageviews', 3, 2],
            'session_start' => ['visits', 2, 2],
            'view_search_results' => ['site_search', 1, 1],
        ], $this->rows('2025-01-06'));
    }

    public function test_rows_segmentOnTheirMatomoData(): void
    {
        $segments = [];
        foreach ($this->table('2025-01-06') as $row) {
            $segments[$row['label']] = $row['segment'] ?? false;
        }

        $this->assertSame('actionType==outlinks', $segments['click']);
        $this->assertSame('actionType==downloads', $segments['file_download']);
        $this->assertSame('visitorType==new', $segments['first_visit']);
        $this->assertFalse($segments['session_start']);
    }

    public function test_weekPeriod_sumsTheDays(): void
    {
        $rows = $this->rows('2025-01-06', 'week');

        $this->assertSame(['downloads', 2, 2], $rows['file_download']);
        $this->assertSame(['pageviews', 4, 3], $rows['page_view']);
        $this->assertSame(['visits', 3, 3], $rows['session_start']);
        $this->assertSame(['new_visits', 2, 2], $rows['first_visit']);
    }

    public function test_videoEvents_areDerivedFromMediaAnalyticsPlays(): void
    {
        if (!Manager::getInstance()->isPluginActivated('MediaAnalytics')) {
            $this->markTestSkipped('Media Analytics is not activated');
        }

        $idVisit = (int) Db::fetchOne('SELECT idvisit FROM ' . Common::prefixTable('log_visit') . ' WHERE idsite = ? ORDER BY idvisit LIMIT 1', [$this->idSite]);

        // progress / length: 30% (10, 25), 100% (all milestones + complete), 59 of 60 (complete), no length, too short
        $this->insertPlay($idVisit, 'p00001', 30, 100, 20);
        $this->insertPlay($idVisit, 'p00002', 100, 100, 90);
        $this->insertPlay($idVisit, 'p00003', 59, 60, 50);
        $this->insertPlay($idVisit, 'p00004', 40, 0, 40);
        $this->insertPlay($idVisit, 'p00005', 1, 100, 1);

        $rows = $this->rows('2025-01-06');

        $this->assertSame(['video_plays', 4, 1], $rows['video_start']);
        $this->assertSame(['video_progress', 10, 1], $rows['video_progress']);
        $this->assertSame(['video_complete', 2, 1], $rows['video_complete']);
    }

    public function test_formEvents_areDerivedFromFormAnalyticsForms(): void
    {
        if (!Manager::getInstance()->isPluginActivated('FormAnalytics')) {
            $this->markTestSkipped('Form Analytics is not activated');
        }

        $idVisit = (int) Db::fetchOne('SELECT idvisit FROM ' . Common::prefixTable('log_visit') . ' WHERE idsite = ? ORDER BY idvisit LIMIT 1', [$this->idSite]);

        // starts / submissions: started twice and submitted once, started without submitting, only viewed
        $this->insertForm($idVisit, 'f00001', 2, 1);
        $this->insertForm($idVisit, 'f00002', 1, 0);
        $this->insertForm($idVisit, 'f00003', 0, 0);

        $rows = $this->rows('2025-01-06');

        $this->assertSame(['form_starts', 3, 1], $rows['form_start']);
        $this->assertSame(['form_submissions', 1, 1], $rows['form_submit']);
    }

    public function test_videoAndFormEvents_areDerivedUnderASegment(): void
    {
        if (!Manager::getInstance()->isPluginActivated('MediaAnalytics') || !Manager::getInstance()->isPluginActivated('FormAnalytics')) {
            $this->markTestSkipped('Media Analytics and Form Analytics are not activated');
        }

        $idVisit = (int) Db::fetchOne('SELECT idvisit FROM ' . Common::prefixTable('log_visit') . ' WHERE idsite = ? ORDER BY idvisit LIMIT 1', [$this->idSite]);

        $this->insertPlay($idVisit, 'p00001', 100, 100, 90);
        $this->insertForm($idVisit, 'f00001', 2, 1);

        // The segment wraps the query in a subselect, which used to fail on the outer GROUP BY idsite
        $rows = $this->rows('2025-01-06', 'day', 'visitorType==new');

        $this->assertSame(['video_plays', 1, 1], $rows['video_start']);
        $this->assertSame(['form_starts', 2, 1], $rows['form_start']);
    }

    private function insertForm(int $idVisit, string $idFormView, int $starts, int $submissions): void
    {
        Db::query(
            'INSERT INTO ' . Common::prefixTable('log_form') . ' (idsiteform, idsite, idvisit, idvisitor, first_idformview, last_idformview,'
            . ' num_views, num_starts, num_submissions, form_last_action_time)'
            . ' VALUES (1, ?, ?, ?, ?, ?, 1, ?, ?, ?)',
            [$this->idSite, $idVisit, hex2bin('a000000000000001'), $idFormView, $idFormView, $starts, $submissions, '2025-01-06 09:20:00']
        );
    }

    private function insertPlay(int $idVisit, string $idView, int $progress, int $length, int $watchedTime): void
    {
        Db::query(
            'INSERT INTO ' . Common::prefixTable('log_media') . ' (idvisitor, idvisit, idsite, idview, player_name, media_type, fullscreen,'
            . ' media_title, resource, server_time, watched_time, media_progress, media_length)'
            . ' VALUES (?, ?, ?, ?, ?, 1, 0, ?, ?, ?, ?, ?, ?)',
            [hex2bin('a000000000000001'), $idVisit, $this->idSite, $idView, 'html5', 'Demo', 'https://example.com/demo.mp4', '2025-01-06 09:10:00', $watchedTime, $progress, $length]
        );
    }

    private function rows(string $date, string $period = 'day', string $segment = ''): array
    {
        $rows = [];
        foreach ($this->table($date, $period, $segment) as $row) {
            $rows[$row['label']] = [
                $row['source'],
                (int) $row['nb_events'],
                (int) $row['nb_visits'],
            ];
        }
        ksort($rows);

        return $rows;
    }

    private function table(string $date, string $period = 'day', string $segment = ''): array
    {
        $json = Request::processRequest(static::PLUGIN . '.getEventsDerivedFromActions', [
            'idSite' => $this->idSite,
            'period' => $period,
            'date' => $date,
            'format' => 'json',
            'filter_limit' => -1,
            'segment' => $segment,
        ]);

        return json_decode($json, true);
    }

    private function tracker(string $visitorId, string $dateTime): \MatomoTracker
    {
        $tracker = Fixture::getTracker($this->idSite, $dateTime, true, true);
        $tracker->setVisitorId($visitorId);
        $tracker->setUrl('https://example.com/');

        return $tracker;
    }

    private function atTime(\MatomoTracker $tracker, string $dateTime): \MatomoTracker
    {
        $tracker->setForceVisitDateTime($dateTime);

        return $tracker;
    }
}
