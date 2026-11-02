<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\EventsEnhanced\tests\Integration;

use Piwik\API\Request;
use Piwik\Container\StaticContainer;
use Piwik\DataTable\Map;
use Piwik\FrontController;
use Piwik\Plugins\API\ProcessedReport;
use Piwik\Tests\Framework\Fixture;
use Piwik\Tests\Framework\Mock\FakeAccess;
use Piwik\Tests\Framework\TestCase\IntegrationTestCase;

/**
 * The row evolution icon of the event detail tables. The popover goes through three steps which all have to know
 * the event the table is scoped to: the table hands it to its row action, the row action keeps it in the popover
 * request, and the core report lookup must still find a report which declares parameters.
 *
 * @group EventsEnhanced
 * @group EventsEnhancedRowEvolutionTest
 * @group Plugins
 */
class RowEvolutionTest extends IntegrationTestCase
{
    private const SCOPED_REPORTS = [
        'getEventActionsForCategory' => ['eventCategory', 'Episode', 'category'],
        'getEventNamesForCategory' => ['eventCategory', 'Episode', 'category'],
        'getEventCategoriesForAction' => ['eventAction', 'View', 'action'],
        'getEventNamesForAction' => ['eventAction', 'View', 'action'],
        'getEventCategoriesForName' => ['eventName', 'Ep1', 'name'],
        'getEventActionsForName' => ['eventName', 'Ep1', 'name'],
        'getEventValuesForCategory' => ['eventCategory', 'Episode', 'category'],
        'getEventValuesForAction' => ['eventAction', 'View', 'action'],
        'getEventValuesForName' => ['eventName', 'Ep1', 'name'],
    ];

    protected $idSite;

    public function setUp(): void
    {
        parent::setUp();

        Fixture::createSuperUser();
        FakeAccess::clearAccess(true);
        $this->idSite = (int) Fixture::createWebsite('2024-12-01 00:00:00');

        $this->trackEvents('a000000000000001', '2025-01-06 09:00:00', [
            ['Episode', 'View', 'Ep1'],
            ['Episode', 'View', 'Ep1'],
            ['Episode', 'View', 'Ep2'],
            ['Series', 'Click', 'Ep1'],
        ]);
        $this->trackEvents('b000000000000002', '2025-01-07 10:00:00', [
            ['Episode', 'View', 'Ep1'],
        ]);
    }

    /**
     * @dataProvider getScopedReports
     */
    public function test_scopedReport_returnsTheEvolutionOfTheRow(string $action, array $parameters, string $label, array $expected): void
    {
        $this->assertSame($expected, $this->getEvolution($action, $label, $parameters));
    }

    public static function getScopedReports(): array
    {
        return [
            'actions for a category' => ['getEventActionsForCategory', ['eventCategory' => 'Episode'], 'View', [3, 1, 0]],
            'names for a category' => ['getEventNamesForCategory', ['eventCategory' => 'Episode'], 'Ep1', [2, 1, 0]],
            'categories for an action' => ['getEventCategoriesForAction', ['eventAction' => 'View'], 'Episode', [3, 1, 0]],
            'names for an action' => ['getEventNamesForAction', ['eventAction' => 'View'], 'Ep1', [2, 1, 0]],
            'categories for a name' => ['getEventCategoriesForName', ['eventName' => 'Ep1'], 'Episode', [2, 1, 0]],
            'actions for a name' => ['getEventActionsForName', ['eventName' => 'Ep1'], 'View', [2, 1, 0]],
            'only counts the event of the table' => ['getEventNamesForAction', ['eventAction' => 'Click'], 'Ep1', [1, 0, 0]],
        ];
    }

    public function test_scopedReport_findsValuesWithSpecialCharacters(): void
    {
        $name = "Mise en place d'un tracking";
        $this->trackEvents('c000000000000003', '2025-01-08 10:00:00', [['Guide', "L'action", $name]]);

        $this->assertSame([0, 0, 1], $this->getEvolution('getEventActionsForName', "L'action", ['eventName' => $name]));
    }

    public function test_valueReports_returnTheEvolutionOfTheRow(): void
    {
        $tracker = Fixture::getTracker($this->idSite, '2025-01-08 10:00:00', true, true);
        $tracker->setVisitorId('d000000000000004');
        $tracker->setUrl('https://example.com/episodes');
        Fixture::checkResponse($tracker->doTrackEvent('Guide', 'Read', 'Manual', 5));

        $this->assertSame([0, 0, 1], $this->getEvolution('getEventValuesForCategory', '5', ['eventCategory' => 'Guide']));
        $this->assertSame([0, 0, 1], $this->getEvolution('getEventValuesForAction', '5', ['eventAction' => 'Read']));
        $this->assertSame([0, 0, 1], $this->getEvolution('getEventValuesForName', '5', ['eventName' => 'Manual']));
    }

    public function test_scopedReport_comparesSeveralRows(): void
    {
        $report = $this->requestRowEvolution('getEventNamesForCategory', 'Ep1,Ep2', ['eventCategory' => 'Episode', 'column' => 'nb_events']);

        $this->assertCount(2, $report['metadata']['metrics']);
    }

    public function test_reportMetadata_keepsTheParametersOutsideRowEvolution(): void
    {
        $reports = StaticContainer::get(ProcessedReport::class)->getReportMetadata($this->idSite, 'day', '2025-01-06', false, true);

        $parameters = [];
        foreach ($reports as $report) {
            if ($report['module'] === 'EventsEnhanced' && isset(self::SCOPED_REPORTS[$report['action']])) {
                $parameters[$report['action']] = $report['parameters'];
            }
        }

        $this->assertCount(count(self::SCOPED_REPORTS), $parameters);
        foreach (self::SCOPED_REPORTS as $action => [$parameter]) {
            $this->assertSame([$parameter => ''], $parameters[$action], $action);
        }
    }

    /**
     * The row action only reads the event from the parameters the table hands to the browser
     */
    public function test_table_handsTheEventToItsRowAction(): void
    {
        foreach (self::SCOPED_REPORTS as $action => [$parameter, $value, $dimensionType]) {
            $tableParameters = $this->getTableParameters($this->renderTable($action, $dimensionType, $value));

            $this->assertSame($value, $tableParameters[$parameter] ?? null, $action);
        }
    }

    /**
     * Core drops every popover parameter it does not know, the row action must declare the ones of the reports
     */
    public function test_rowAction_declaresTheParameterOfEveryScopedReport(): void
    {
        $script = file_get_contents(__DIR__ . '/../../javascripts/eventsEnhancedRowEvolution.js');

        foreach (self::SCOPED_REPORTS as $action => [$parameter]) {
            $this->assertStringContainsString("'$parameter'", $script, $action);
        }
        $this->assertStringContainsString("'dimensionType'", $script);
        $this->assertStringContainsString("'dimensionValue'", $script);
    }

    /**
     * What the browser reads as dataTable.param
     */
    private function getTableParameters(string $html): array
    {
        $this->assertSame(1, preg_match('/data-params=(["\'])(.*?)\1/s', $html, $match));

        return json_decode(html_entity_decode($match[2], ENT_QUOTES), true);
    }

    private function renderTable(string $action, string $dimensionType, string $value): string
    {
        $backup = $_GET;
        $_GET = [
            'module' => 'EventsEnhanced',
            'action' => $action,
            'idSite' => $this->idSite,
            'period' => 'day',
            'date' => '2025-01-06',
            'eventDimensionType' => $dimensionType,
            'eventDimensionValue' => $value,
        ];

        try {
            return (string) FrontController::getInstance()->fetchDispatch('EventsEnhanced', $action);
        } finally {
            $_GET = $backup;
        }
    }

    private function getEvolution(string $action, string $label, array $parameters): array
    {
        $map = $this->requestRowEvolution($action, $label, $parameters)['reportData'];
        $this->assertInstanceOf(Map::class, $map);

        $values = [];
        foreach ($map->getDataTables() as $table) {
            $row = $table->getFirstRow();
            $values[] = $row ? (int) $row->getColumn('nb_events') : 0;
        }

        return $values;
    }

    private function requestRowEvolution(string $action, string $label, array $parameters): array
    {
        return Request::processRequest('API.getRowEvolution', array_merge([
            'idSite' => $this->idSite,
            'period' => 'day',
            'date' => '2025-01-06,2025-01-08',
            'apiModule' => 'EventsEnhanced',
            'apiAction' => $action,
            'label' => $label,
            'format' => 'original',
            'serialize' => 0,
        ], $parameters), []);
    }

    private function trackEvents(string $visitorId, string $dateTime, array $events): void
    {
        $tracker = Fixture::getTracker($this->idSite, $dateTime, true, true);
        $tracker->setVisitorId($visitorId);
        $tracker->setUrl('https://example.com/episodes');

        foreach ($events as $i => [$category, $action, $name]) {
            $tracker->setForceVisitDateTime(date('Y-m-d H:i:s', strtotime($dateTime) + 60 * $i));
            Fixture::checkResponse($tracker->doTrackEvent($category, $action, $name));
        }
    }
}
