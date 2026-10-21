## FAQ

**I just installed the plugin, where is my history?**

Activating the plugin schedules its reports for past periods (EventsEnhanced Premium does the same for its own reports): the next `core:archive` run rebuilds them from the raw logs, nothing to do. How far back follows the `rearchive_reports_in_past_last_n_months` setting of Matomo (6 months by default). To go further, set it in the `[General]` section of `config/config.ini.php` before activating the plugin, or invalidate older periods with the commands below. Periods whose raw logs were deleted (`delete_logs_older_than`) cannot be rebuilt.

**What is the Events derived from actions report?**

It lists the events GA4 collects automatically (`page_view`, `click`, `file_download`, `video_start`...), rebuilt from Matomo pageviews, outlinks, downloads, site searches, visits, Media Analytics plays and Form Analytics forms, so you can compare Matomo with GA4. These rows are not stored as Matomo events: your own events stay in Events (All Dimensions) and the other Events reports, the two never mix.

**Why do my Media Analytics events show video_start instead of the video title?**

The plugin renames the automatic Media Analytics events after the GA4 event names (`video_start`, `video_complete`...), so the Event Names report counts interactions instead of listing titles. The action is capitalized (`Play`, `Finish`...). Per video figures are in the Media reports of Media Analytics. Events you track yourself are renamed only when they use the `MediaVideo` or `MediaAudio` category with one of the actions `play`, `pause`, `resume`, `seek` or `finish`.

**How do I access the event detail pages?**

Open any Events report (Behavior > Events), hover a row and click the "View Event Details" icon.

**Why is a detail page empty for past periods?**

The reports are built during archiving. Periods archived before the plugin was activated have no data until you invalidate and re-archive them, for every period type (the default of the command), for example:

```
./console core:invalidate-report-data --sites=1 --dates=2025-01-01,2025-12-31 --plugin=EventsEnhanced
./console core:archive
```

Adjust `--sites` and `--dates` to your data. Keep the default periods: a month is built from its week archives, so a week archived before the plugin was active makes the month incomplete.

**I updated the plugin, why do past reports still show the old numbers?**

Version 6.0.1 fixes how the reports are archived: events without a name are counted under "Event Name not defined", the event value 0 is kept, the "Others" row of a truncated list is shown as "Others", and values with an apostrophe or another special character find their data. Reports archived before the update keep the old numbers until you invalidate and re-archive them with the commands above.

**Why don't I see Custom Dimensions on the detail pages?**

Custom Dimensions reports (premium) need the CustomDimensions plugin, at least one action-scoped Custom Dimension, and data for that dimension on the selected events.

**Can I segment the detail page reports?**

Yes, the segment, period and date selected in Matomo apply to every report of the detail page.

**Does this plugin require any special permissions?**

No. Anyone who can view the Events reports of a site can open its detail pages.

**Which versions of Matomo are supported?**

Version 6.x of the plugin supports Matomo 6 (PHP 8.1 or later). Use the 5.x versions of the plugin for Matomo 5.
