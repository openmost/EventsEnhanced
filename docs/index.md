## Documentation

EventsEnhanced adds detailed analytics pages for event dimension values. When viewing Events reports, you can click on any category, action, or name value to see comprehensive analytics for that specific value.

### Detail Pages

Each detail page includes:

1. **Evolution Graph**: Shows how the event dimension value has trended over the selected period, with the ability to select different metrics
2. **Related Dimension Reports**:
   - For Category pages: Actions and Names reports filtered to that category
   - For Action pages: Categories and Names reports filtered to that action
   - For Name pages: Categories and Actions reports filtered to that name
3. **Page Context ([premium](https://openmost.io/products/events-enhanced/?utm_source=matomo_installed_plugin&utm_medium=plugin_events_enhanced_documentation&utm_campaign=plugin_premium_events_enhanced))**: Page URLs and Page Titles where events with this dimension value were actually triggered (not just pages visited during sessions with events)
4. **Custom Dimensions ([premium](https://openmost.io/products/events-enhanced/?utm_source=matomo_installed_plugin&utm_medium=plugin_events_enhanced_documentation&utm_campaign=plugin_premium_events_enhanced))**: If you have action-scoped Custom Dimensions, their values are displayed for events matching the selected dimension value

[Purchase EventsEnhanced Premium version](https://openmost.io/products/events-enhanced/?utm_source=matomo_installed_plugin&utm_medium=plugin_events_enhanced_documentation&utm_campaign=plugin_premium_events_enhanced)

### API Methods

The plugin adds the following API methods:

- `EventsEnhanced.getEventActionsForCategory` - Get event actions for a specific category
- `EventsEnhanced.getEventNamesForCategory` - Get event names for a specific category
- `EventsEnhanced.getEventCategoriesForAction` - Get event categories for a specific action
- `EventsEnhanced.getEventNamesForAction` - Get event names for a specific action
- `EventsEnhanced.getEventCategoriesForName` - Get event categories for a specific name
- `EventsEnhanced.getEventActionsForName` - Get event actions for a specific name
- `EventsEnhanced.getPageUrlsForEvent` (premium) - Get page URLs where events were triggered (archived data)
- `EventsEnhanced.getPageTitlesForEvent` (premium) - Get page titles where events were triggered (archived data)
- `EventsEnhanced.getCustomDimensionForEvent` (premium) - Get custom dimension values for events (archived data)
- `EventsEnhanced.getAvailableCustomDimensions` (premium) - Get action-scoped custom dimensions for a site
