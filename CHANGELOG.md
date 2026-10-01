## Changelog

### v5.1.0

- Fix: event categories, actions and names with an apostrophe or another special character are shown as is in the Event value selector, they were escaped twice (`d&#039;un` instead of `d'un`). Their detail page no longer lists the value twice in the selector, and its reports now find their data.
- Data quality fixes in the archived reports. Events without a name are now counted under "Event Name not defined" in every breakdown, they were dropped from the reports by event name and from the event names of the category and action pages, so these lists added up to less than the event total. The event value 0 is no longer dropped from the event value reports. Event value sums are rounded like in the core Events reports. The "Others" row of a truncated list stays an "Others" row on the detail pages instead of a row labelled -1. A failed archiving query now fails the archive, so it is retried, instead of storing an incomplete report.
- Existing installs must invalidate and re-archive past periods to get the corrected numbers: `./console core:invalidate-report-data --sites=1 --dates=2025-01-01,2025-12-31 --plugin=EventsEnhanced` then `./console core:archive`. Keep the default periods: a month is built from its week archives, a week archived before the plugin was active has no data for the plugin and makes the month incomplete.
- The version number now follows the Matomo major version (5.x for Matomo 5).
- The Event Categories and Event Actions evolution reports now declare a report category, so their metadata is complete for API and AI clients such as the McpServer plugin. They still only appear from the Related Reports links.
- Premium report previews load their images relative to Matomo, fixing broken images when Matomo is installed in a sub directory
- Translated into 12 languages
- Plugin homepage, support email and documentation moved to openmost.com
- The Premium links of the documentation and of the report previews open the EventsEnhanced page of openmost.com, the version notes follow the new 5.x / 6.x numbering
- Shorter Marketplace description that fits the plugin cards, and campaign parameters on the Openmost links of the README.

### v1.1.0

#### New features
- add: Evolution graphs for top 10 event names, categories, and actions over time
- add: 2 additional series colors (brown 800, blue grey 800) to display 10 curves on evolution graphs
- add: Plugin dependency declaration for Events plugin
- add: Vue shims and TypeScript configuration for reliable builds

#### Improvements
- update: API layer refactored, 9 near-identical methods consolidated into a single `getSubtableForDimension()` helper
- update: Controller refactored, merged duplicate table configuration methods into `configureEventDimensionTable()`
- update: Added input validation for `dimensionType` parameter across all controller actions
- update: RecordBuilder `EventDimensionRelations` split into 2 optimized SQL queries, dimension reports no longer include `custom_float` in GROUP BY, fixing inflated visit counts
- update: Added RankingQuery support to both RecordBuilders for large dataset handling
- update: Removed premature `round()` during archiving in `EventsAllDimensions` to preserve full value precision
- update: Removed `Common::getRequestVar()` calls from Report `init()` methods (constructors should not access HTTP request)
- update: Replaced hardcoded "Custom Dimension" labels with `translate('CustomDimensions_CustomDimensionId')` for proper i18n
- update: Plugin category changed from "database" to "analytics"
- update: Vue source files converted to LF line endings for ESLint compatibility
- update: Removed "Did you know?" promotional footer from all reports and evolution graphs

#### Bug fixes
- fix: Evolution graphs now correctly limit to top 10 series globally across all periods (previously each period had independent top N, causing inconsistent series)
- fix: CSS typo in GoPremiumWidget (`100ù` changed to `100%`)
- fix: Flattened nested CSS selectors in GoPremiumWidget for broader browser compatibility
- fix: Removed hardcoded `tabindex="6"` from GoPremiumWidget (accessibility anti-pattern)
- fix: GoPremiumWidget image path now uses computed `matomoBaseUrl` instead of hardcoded path
- fix: Replaced deprecated `piwik.*` globals with `Matomo.*` in row action JavaScript
- fix: Used `Object.prototype.hasOwnProperty.call()` instead of direct `.hasOwnProperty()` in row action
- fix: Removed duplicate inline `style` attribute from DimensionSelectors component
- fix: Fixed `fr.json` indentation inconsistency

#### Maintenance
- add: `.gitignore` for `.DS_Store` and `.claude/` files
- add: Error logging via `LoggerInterface` in API and RecordBuilder exception handlers
- remove: Dead code (`filterAndFlattenNestedSubtable`, `extractNestedSubtableByLabel`) from API
- remove: Unused `Common` import from 6 Report files

### v1.0.4

- update: Plugin name to match marketplace requirements

### v1.0.3

- update: Plugin description to match marketplace requirements

### v1.0.2

- update: Define plugin licence to GPL v3+

### v1.0.1

- add: Cover image for Matomo marketplace

### v1.0.0

Plugin release, read README.md for more information.