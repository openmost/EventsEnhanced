## Documentation

EventsEnhanced adds a detail page for every event category, action and name. From any Events report (Behavior > Events), hover a row and click the "View Event Details" icon.

### Detail pages

Each detail page includes:

1. **Dimension selectors** to switch between category, action and name, and between values, without leaving the page.
2. **Evolution graph** of the selected value, with a metric picker (events, visits, event value, events with a value).
3. **Related breakdowns**:
   - Category pages: actions and names filtered on that category
   - Action pages: categories and names filtered on that action
   - Name pages: categories and actions filtered on that name
   - Event values recorded for the selected value
4. **Page URLs and Page titles (premium)**: the pages where the selected events were actually triggered, not every page of the visits that contained them.
5. **Countries (premium)**: the countries of the visitors who triggered them.
6. **Custom Dimensions (premium)**: values of your action-scoped Custom Dimensions for the selected events.

The free plugin shows previews of the premium reports. [EventsEnhanced Premium](https://openmost.com/matomo/extensions/events-enhanced?utm_source=matomo_marketplace&utm_medium=referral&utm_campaign=premium_upgrade&utm_content=eventsenhanced) unlocks them.

### Additional reports

- **Events (All Dimensions)**: category, action and name in one hierarchical table under Behavior > Events, also available as a dashboard widget.
- **Event Names Evolution (top 10)** under Behavior > Events, plus **Event Categories Evolution** and **Event Actions Evolution** from the Related Reports links.

### API methods

- `EventsEnhanced.getEventActionsForCategory`, `EventsEnhanced.getEventNamesForCategory`
- `EventsEnhanced.getEventCategoriesForAction`, `EventsEnhanced.getEventNamesForAction`
- `EventsEnhanced.getEventCategoriesForName`, `EventsEnhanced.getEventActionsForName`
- `EventsEnhanced.getEventValuesForCategory`, `EventsEnhanced.getEventValuesForAction`, `EventsEnhanced.getEventValuesForName`
- `EventsEnhanced.getEventsWithAllDimensions`
- `EventsEnhanced.getEventNamesEvolution`, `EventsEnhanced.getEventCategoriesEvolution`, `EventsEnhanced.getEventActionsEvolution`

### Archiving

Reports are built during archiving, so data appears for periods archived after activation. To fill past periods, or to get the corrected numbers of the latest version for periods archived before the update, invalidate and re-archive them as described in the FAQ.
