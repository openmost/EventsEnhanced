# EventsEnhanced

Turn Matomo's Events reports into a navigable analysis: open a detail page for any event category, action or name and see how it evolves and what it is combined with.

## Features

- **Event detail pages**: a "View Event Details" row action on the Events reports (Categories, Actions, Names) opens a dedicated page for the selected value. Selectors at the top of the page let you switch the dimension (category, action, name) and the value without going back to the report.
- **Evolution graph** of the selected value over time, with a metric picker (events, visits, event value, events with a value).
- **Related breakdowns** on each detail page: the two other event dimensions filtered on the selected value (for example the actions and names of a category) and the event values recorded for it.
- **Events (All Dimensions) report** under Behavior > Events: category, action and name in one hierarchical table, with visits, unique visitors, events, total, minimum, maximum and average event value. It is also available as a dashboard widget.
- **Top 10 evolution graphs** for event names (under Behavior > Events), and for event categories and actions (from the Related Reports links), with one line per value.
- **API methods** for every breakdown, for example `EventsEnhanced.getEventActionsForCategory`, `EventsEnhanced.getEventNamesForAction`, `EventsEnhanced.getEventValuesForName`, `EventsEnhanced.getEventsWithAllDimensions` and `EventsEnhanced.getEventNamesEvolution`. Report metadata is complete, so API and AI clients (such as the McpServer plugin) can discover them.
- Segments, periods and the standard view permission of the Events reports apply everywhere.
- Translated into 12 languages.

### Premium version

[EventsEnhanced Premium](https://openmost.com/matomo/extensions/events-enhanced?utm_source=matomo_marketplace&utm_medium=referral&utm_campaign=premium_upgrade&utm_content=eventsenhanced) adds three reports to every detail page, shown as previews in the free version:

- **Page URLs** and **Page titles** where the selected events were actually triggered.
- **Countries** of the visitors who triggered them.
- **Custom Dimensions**: values of your action-scoped Custom Dimensions for the selected events.

## Requirements

- Matomo 5.x (5.0.0 or later, below 6.0.0)
- The Events plugin (bundled with Matomo and enabled by default).

## Installation / Configuration

1. Install EventsEnhanced from the Marketplace (Administration > Platform > Marketplace), or copy the `EventsEnhanced` folder into the `plugins` directory of your Matomo installation.
2. Activate it in Administration > Plugins.
3. Open Behavior > Events, hover a row of any Events report and click the "View Event Details" icon.

There are no settings. Reports are built during archiving, so data appears for periods archived after activation. To see past periods, or to get the corrected numbers of this version for periods archived before an update, invalidate and re-archive them, for every period type (the default of the command), for example `./console core:invalidate-report-data --sites=1 --dates=2025-01-01,2025-12-31 --plugin=EventsEnhanced` then `./console core:archive`.

## Privacy and data

All reports are computed inside your Matomo database from the events you already track. The plugin sends no data to third parties.

## Need help with Matomo?

Openmost is an official Matomo Implementation Partner. Event reports are only as useful as the tracking behind them: we design [Matomo tracking plans](https://openmost.com/matomo/services/tracking-architecture?utm_source=matomo_marketplace&utm_medium=referral&utm_campaign=services&utm_content=eventsenhanced) with consistent event naming, goals and custom dimensions, then implement them in your tag manager.

## Support

- Email: ronan@openmost.com
- Homepage: https://openmost.com/matomo/extensions/events-enhanced
- Issues: https://github.com/openmost/EventsEnhanced/issues

## Screenshots

Screenshots of the Events reports and of an event detail page are available in the `screenshots` folder and on the Marketplace.

## License

GPL v3 or later
