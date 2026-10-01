<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\EventsEnhanced\tests\Integration;

use Piwik\API\Request;
use Piwik\Config;
use Piwik\Piwik;
use Piwik\Tests\Framework\Fixture;
use Piwik\Tests\Framework\Mock\FakeAccess;
use Piwik\Tests\Framework\TestCase\IntegrationTestCase;

/**
 * Archives a small, fully known data set and checks every report against the expected counts,
 * against the core Events reports, and that each subtable adds up to its parent row.
 *
 * Site timezone is Europe/Paris: an event tracked 2024-12-31 23:30 UTC belongs to 2025-01-01,
 * an event tracked 2025-01-31 23:30 UTC belongs to February.
 *
 * @group EventsEnhanced
 * @group EventsEnhancedArchivingTest
 * @group Plugins
 */
class ArchivingTest extends IntegrationTestCase
{
    protected const PLUGIN = 'EventsEnhanced';

    private const DESKTOP_UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
    private const SMARTPHONE_UA = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1';

    protected $idSite;

    public function setUp(): void
    {
        parent::setUp();

        Fixture::createSuperUser();
        FakeAccess::clearAccess(true);
        $this->idSite = (int) Fixture::createWebsite('2024-12-01 00:00:00', 0, false, false, 1, null, null, 'Europe/Paris');

        $this->trackVisit('a000000000000001', '2025-01-06 09:00:00', self::DESKTOP_UA, [
            ['Episode', 'View', 'Ep1', 2],
            ['Episode', 'View', 'Ep1', 2],
            ['Episode', 'View', null, null],
            ['Episode', 'Click', 'Ep1', 0],
        ]);
        $this->trackVisit('b000000000000002', '2025-01-07 10:00:00', self::SMARTPHONE_UA, [
            ['Episode', 'View', 'Ep2', 3],
            ['Series', 'View', '0', null],
        ]);
        $this->trackVisit('a000000000000001', '2025-01-14 10:00:00', self::DESKTOP_UA, [
            ['Episode', 'View', 'Ep1', null],
        ]);
        $this->trackVisit('c000000000000003', '2024-12-31 23:30:00', self::SMARTPHONE_UA, [
            ['Episode', 'View', 'Ep3', 1.5],
        ]);
        $this->trackVisit('d000000000000004', '2025-01-31 23:30:00', self::DESKTOP_UA, [
            ['Episode', 'View', 'Ep1', null],
        ]);
    }

    public function test_actionsForCategory_matchTheTrackedEvents_forEveryPeriodType(): void
    {
        $expected = [
            ['day', '2025-01-01', ['View' => 1]],
            ['day', '2025-01-06', ['Click' => 1, 'View' => 3]],
            ['week', '2025-01-06', ['Click' => 1, 'View' => 4]],
            ['month', '2025-01-01', ['Click' => 1, 'View' => 6]],
            ['range', '2025-01-06,2025-01-14', ['Click' => 1, 'View' => 5]],
        ];

        foreach ($expected as [$period, $date, $counts]) {
            $rows = $this->getRows('getEventActionsForCategory', $period, $date, ['eventCategory' => 'Episode']);
            $this->assertSame($counts, $this->column($rows, 'nb_events'), "$period $date");
            $this->assertSame($this->getCoreSubtable('Events.getCategory', 'eventAction', 'Episode', $period, $date), $this->column($rows, 'nb_events'), "core $period $date");
        }
    }

    public function test_namesForCategory_keepEventsWithoutName_soTheSubtableAddsUpToTheCategory(): void
    {
        $rows = $this->getRows('getEventNamesForCategory', 'month', '2025-01-01', ['eventCategory' => 'Episode']);
        $names = $this->column($rows, 'nb_events');

        $this->assertSame(4, $names['Ep1']);
        $this->assertSame(1, $names['Ep2']);
        $this->assertSame(1, $names['Ep3']);
        $this->assertSame(1, $names[$this->getNameNotSetLabel()]);
        $this->assertSame(7, array_sum($names));
        $this->assertEquals(7, $this->getCoreRow('Events.getCategory', 'Episode', 'month', '2025-01-01')['nb_events']);
    }

    public function test_reportsKeyedByValue_findValuesWithSpecialCharacters(): void
    {
        // The archive keeps the HTML escaping of the tracked values (d&#039;un), the detail page sends them unescaped.
        $name = "Mise en place d'un tracking";
        $this->trackVisit('e000000000000005', '2025-01-08 10:00:00', self::DESKTOP_UA, [
            ['Guide', "L'action", $name, null],
        ]);

        $rows = $this->getRows('getEventCategoriesForName', 'month', '2025-01-01', ['eventName' => $name]);
        $this->assertSame(['Guide' => 1], $this->column($rows, 'nb_events'));

        $rows = $this->getRows('getEventCategoriesForAction', 'month', '2025-01-01', ['eventAction' => "L'action"]);
        $this->assertSame(['Guide' => 1], $this->column($rows, 'nb_events'));
    }

    public function test_reportsKeyedByName_findEventsWithoutName(): void
    {
        // the tracker stores the name "0" as no name, like core Events
        $rows = $this->getRows('getEventCategoriesForName', 'month', '2025-01-01', ['eventName' => $this->getNameNotSetLabel()]);
        $this->assertSame(['Episode' => 1, 'Series' => 1], $this->column($rows, 'nb_events'));
        $this->assertEquals(2, $this->getCoreRow('Events.getName', $this->getNameNotSetLabel(), 'month', '2025-01-01')['nb_events']);

        $rows = $this->getRows('getEventActionsForName', 'month', '2025-01-01', ['eventName' => 'Ep1']);
        $this->assertSame(['Click' => 1, 'View' => 3], $this->column($rows, 'nb_events'));
    }

    public function test_valueReports_keepTheZeroValue_andMatchTheValueTotals(): void
    {
        $rows = $this->getRows('getEventValuesForAction', 'month', '2025-01-01', ['eventAction' => 'Click']);
        $this->assertSame(['0' => 1], $this->column($rows, 'nb_events'));

        $rows = $this->getRows('getEventValuesForCategory', 'month', '2025-01-01', ['eventCategory' => 'Episode']);
        $this->assertSame(['0' => 1, '1.5' => 1, '2' => 2, '3' => 1], $this->column($rows, 'nb_events'));
        $this->assertEqualsWithDelta(8.5, array_sum($this->column($rows, 'sum_event_value')), 0.001);

        $core = $this->getCoreRow('Events.getCategory', 'Episode', 'month', '2025-01-01');
        $this->assertEquals(5, $core['nb_events_with_value']);
        $this->assertEqualsWithDelta(8.5, $core['sum_event_value'], 0.001);
        $this->assertSame(5, array_sum($this->column($rows, 'nb_events_with_value')));
    }

    public function test_allDimensions_matchesCore_andEachLevelAddsUpToItsParent(): void
    {
        foreach ([['day', '2025-01-06'], ['week', '2025-01-06'], ['month', '2025-01-01'], ['range', '2025-01-06,2025-01-14']] as [$period, $date]) {
            $table = Request::processRequest(static::PLUGIN . '.getEventsWithAllDimensions', [
                'idSite' => $this->idSite, 'period' => $period, 'date' => $date, 'expanded' => 1,
                'format' => 'json', 'filter_limit' => -1,
            ]);
            $categories = json_decode($table, true);

            $core = Request::processRequest('Events.getCategory', [
                'idSite' => $this->idSite, 'period' => $period, 'date' => $date, 'format' => 'json', 'filter_limit' => -1,
            ]);
            $this->assertSame($this->column(json_decode($core, true), 'nb_events'), $this->column($categories, 'nb_events'), "$period $date");

            foreach ($categories as $category) {
                $actions = $category['subtable'] ?? [];
                $this->assertEquals($category['nb_events'], array_sum($this->column($actions, 'nb_events')), "$period $date {$category['label']}");
                foreach ($actions as $action) {
                    $this->assertEquals($action['nb_events'], array_sum($this->column($action['subtable'] ?? [], 'nb_events')), "$period $date {$category['label']} {$action['label']}");
                }
            }
        }
    }

    public function test_allDimensions_countsUniqueVisitorsOverTheMonth(): void
    {
        $table = Request::processRequest(static::PLUGIN . '.getEventsWithAllDimensions', [
            'idSite' => $this->idSite, 'period' => 'month', 'date' => '2025-01-01', 'flat' => 1,
            'format' => 'json', 'filter_limit' => -1,
        ]);
        $rows = [];
        foreach (json_decode($table, true) as $row) {
            $rows[$row['label']] = $row;
        }

        $ep1 = $rows['Episode - View - Ep1'];
        $this->assertEquals(3, $ep1['nb_events']);
        $this->assertEquals(2, $ep1['nb_visits']);
        $this->assertEquals(1, $ep1['nb_uniq_visitors']);
    }

    public function test_segment_isAppliedToEveryReport(): void
    {
        $segment = 'deviceType==smartphone';

        $rows = $this->getRows('getEventActionsForCategory', 'month', '2025-01-01', ['eventCategory' => 'Episode', 'segment' => $segment]);
        $this->assertSame(['View' => 2], $this->column($rows, 'nb_events'));

        $rows = $this->getRows('getEventCategoriesForAction', 'month', '2025-01-01', ['eventAction' => 'View', 'segment' => $segment]);
        $this->assertSame(['Episode' => 2, 'Series' => 1], $this->column($rows, 'nb_events'));
        $this->assertSame(
            $this->getCoreSubtable('Events.getAction', 'eventCategory', 'View', 'month', '2025-01-01', $segment),
            $this->column($rows, 'nb_events')
        );
    }

    public function test_truncatedRows_areAggregatedIntoOthers_andTotalsAreKept(): void
    {
        Config::getInstance()->General['datatable_archiving_maximum_rows_subtable_events'] = 2;

        $rows = $this->getRows('getEventNamesForCategory', 'month', '2025-01-01', ['eventCategory' => 'Episode']);
        $names = $this->column($rows, 'nb_events');

        // the limit includes the "Others" row
        $this->assertCount(2, $names);
        $this->assertSame(4, $names['Ep1']);
        $this->assertSame(3, $names[Piwik::translate('General_Others')] ?? $names['-1'] ?? null);
        $this->assertSame(7, array_sum($names));
    }

    protected function getRows(string $method, string $period, string $date, array $params = []): array
    {
        $json = Request::processRequest(static::PLUGIN . '.' . $method, array_merge([
            'idSite' => $this->idSite,
            'period' => $period,
            'date' => $date,
            'format' => 'json',
            'filter_limit' => -1,
        ], $params));

        return json_decode($json, true);
    }

    protected function getCoreRow(string $method, string $label, string $period, string $date): array
    {
        $json = Request::processRequest($method, [
            'idSite' => $this->idSite, 'period' => $period, 'date' => $date, 'format' => 'json', 'filter_limit' => -1,
        ]);
        foreach (json_decode($json, true) as $row) {
            if ($row['label'] === $label) {
                return $row;
            }
        }

        $this->fail("No core row $label");
    }

    protected function getCoreSubtable(string $method, string $secondaryDimension, string $label, string $period, string $date, string $segment = ''): array
    {
        $json = Request::processRequest($method, [
            'idSite' => $this->idSite, 'period' => $period, 'date' => $date, 'format' => 'json', 'filter_limit' => -1,
            'secondaryDimension' => $secondaryDimension, 'expanded' => 1, 'segment' => $segment,
        ]);
        foreach (json_decode($json, true) as $row) {
            if ($row['label'] === $label) {
                return $this->column($row['subtable'] ?? [], 'nb_events');
            }
        }

        return [];
    }

    protected function column(array $rows, string $column): array
    {
        $values = [];
        foreach ($rows as $row) {
            $value = $row[$column] ?? 0;
            $values[(string) $row['label']] = is_numeric($value) ? $value + 0 : $value;
        }
        ksort($values, SORT_STRING);

        return $values;
    }

    protected function getNameNotSetLabel(): string
    {
        return Piwik::translate('General_NotDefined', Piwik::translate('Events_EventName'));
    }

    private function trackVisit(string $visitorId, string $dateTime, string $userAgent, array $events): void
    {
        $tracker = Fixture::getTracker($this->idSite, $dateTime, true, true);
        $tracker->setVisitorId($visitorId);
        $tracker->setUserAgent($userAgent);
        $tracker->setUrl('https://example.com/episodes');

        foreach ($events as $i => [$category, $action, $name, $value]) {
            $tracker->setForceVisitDateTime(date('Y-m-d H:i:s', strtotime($dateTime) + 60 * $i));
            if ($value !== null) {
                $response = $tracker->doTrackEvent($category, $action, $name ?? '', $value);
            } elseif ($name !== null) {
                $response = $tracker->doTrackEvent($category, $action, $name);
            } else {
                $response = $tracker->doTrackEvent($category, $action);
            }
            Fixture::checkResponse($response);
        }
    }
}
