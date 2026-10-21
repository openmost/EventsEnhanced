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

The free plugin shows previews of the premium reports. [EventsEnhanced Premium](https://openmost.com/matomo/extensions/events-enhanced?utm_source=matomo_marketplace&utm_medium=referral&utm_campaign=premium_upgrade&utm_content=eventsenhanced), an add-on installed next to this plugin, replaces them with the real reports. Keep this plugin activated with it: it still archives every common report.

### Additional reports

- **Events (All Dimensions)**: category, action and name in one hierarchical table under Behavior > Events, also available as a dashboard widget.
- **Event Names Evolution (top 10)** under Behavior > Events, plus **Event Categories Evolution** and **Event Actions Evolution** from the Related Reports links.

### Events derived from actions

Behavior > Events > Events derived from actions, below Events (All Dimensions), lists the events GA4 collects automatically, rebuilt from Matomo data. They are not Matomo events: they complement Events (All Dimensions) without mixing with it and do not appear in the other Events reports. The Counted from column shows the Matomo data behind each row.

| GA4 event | Counted from |
|---|---|
| session_start | Visits |
| first_visit | Visits of new visitors |
| page_view | Pageviews |
| click | Outlinks |
| file_download | Downloads |
| view_search_results | Site searches |
| video_start | Media Analytics video plays (more than one second watched, as in Media Analytics) |
| video_progress | Media Analytics video plays, one event per 10, 25, 50 and 75% milestone the furthest position reached |
| video_complete | Media Analytics video plays that reached the end |
| form_start | Form Analytics form starts |
| form_submit | Form Analytics form submissions |

The video rows need Media Analytics and the form rows need Form Analytics. Each row opens the visitor log on its segment (`actionType`, `visitorType==new`, `media_plays_type==video`, `form_num_starts>0` or `form_num_submissions>0`). In the API (`EventsEnhanced.getEventsDerivedFromActions`), the `source` row metadata names the Matomo data of each row (`visits`, `new_visits`, `pageviews`, `outlinks`, `downloads`, `site_search`, `video_plays`, `video_progress`, `video_complete`, `form_starts`, `form_submissions`).

### Media Analytics events

When the Media Analytics plugin is activated, the event name of its automatic events is replaced by the GA4 event name and the action is capitalized while the event is tracked. The category is kept, the media title stays in the Media reports.

| Category | Action | Event name |
|---|---|---|
| MediaVideo | Play | video_start |
| MediaVideo | Pause | video_pause |
| MediaVideo | Resume | video_resume |
| MediaVideo | Seek | video_seek |
| MediaVideo | Finish | video_complete |
| MediaAudio | Play | audio_start |
| MediaAudio | Pause | audio_pause |
| MediaAudio | Resume | audio_resume |
| MediaAudio | Seek | audio_seek |
| MediaAudio | Finish | audio_complete |

Events tracked before the update keep the media title as event name and the lowercase action.

### API methods

- `EventsEnhanced.getEventActionsForCategory`, `EventsEnhanced.getEventNamesForCategory`
- `EventsEnhanced.getEventCategoriesForAction`, `EventsEnhanced.getEventNamesForAction`
- `EventsEnhanced.getEventCategoriesForName`, `EventsEnhanced.getEventActionsForName`
- `EventsEnhanced.getEventValuesForCategory`, `EventsEnhanced.getEventValuesForAction`, `EventsEnhanced.getEventValuesForName`
- `EventsEnhanced.getEventsWithAllDimensions`
- `EventsEnhanced.getEventsDerivedFromActions`
- `EventsEnhanced.getEventNamesEvolution`, `EventsEnhanced.getEventCategoriesEvolution`, `EventsEnhanced.getEventActionsEvolution`

### Archiving

Reports are built during archiving, so data appears for periods archived after activation. To fill past periods, or to get the corrected numbers of the latest version for periods archived before the update, invalidate and re-archive them as described in the FAQ.
