## FAQ

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

Version 5.1.0 fixes how the reports are archived: events without a name are counted under "Event Name not defined", the event value 0 is kept, the "Others" row of a truncated list is shown as "Others", and values with an apostrophe or another special character find their data. Reports archived before the update keep the old numbers until you invalidate and re-archive them with the commands above.

**Why don't I see Custom Dimensions on the detail pages?**

Custom Dimensions reports (premium) need the CustomDimensions plugin, at least one action-scoped Custom Dimension, and data for that dimension on the selected events.

**Can I segment the detail page reports?**

Yes, the segment, period and date selected in Matomo apply to every report of the detail page.

**Does this plugin require any special permissions?**

No. Anyone who can view the Events reports of a site can open its detail pages.

**Which versions of Matomo are supported?**

Version 5.x of the plugin supports Matomo 5 (5.0.0 or later). Use the 6.x versions for Matomo 6.
