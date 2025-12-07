/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

/**
 * This file registers a row action on Events reports to link to EventsEnhanced detail page.
 * Supports:
 * - Native Events reports (Events.getCategory, Events.getAction, Events.getName)
 * - EventsEnhanced 3-dimensions report (EventsEnhanced.getEventsWithAllDimensions)
 */

(function () {
    var actionName = 'openEventDetails';

    // Map of report actions to dimension types
    var reportToDimensionType = {
        // Native Events reports
        'Events.getCategory': 'category',
        'Events.getAction': 'action',
        'Events.getName': 'name',
        // EventsEnhanced 3-dimensions report
        'EventsEnhanced.getEventsWithAllDimensions': 'category'
    };

    function DataTable_RowActions_OpenEventDetails(dataTable) {
        this.dataTable = dataTable;
        this.actionName = actionName;
        this.trEventName = 'matomoTriggerOpenEventDetailsAction';
    }

    DataTable_RowActions_OpenEventDetails.prototype = new DataTable_RowAction();

    DataTable_RowActions_OpenEventDetails.prototype.performAction = function (label, tr, e) {
        var eventValue = label;
        var dimensionType = 'name';

        // Get the report key from dataTable params
        var params = this.dataTable.param;
        var reportKey = '';
        if (params && params.module && params.action) {
            reportKey = params.module + '.' + params.action;
        }

        // Determine dimension type based on the report
        if (reportKey && reportToDimensionType[reportKey]) {
            dimensionType = reportToDimensionType[reportKey];
        }

        // For the 3-dimensions report, we need to determine which column was clicked
        // The report has columns: Category, Action, Name
        if (reportKey === 'EventsEnhanced.getEventsWithAllDimensions') {
            // Get the row data which contains all three dimensions
            var row = tr;

            // Find category, action, name cells by their column index or class
            var categoryCell = row.find('td').eq(0); // First column is Category
            var actionCell = row.find('td').eq(1);   // Second column is Action
            var nameCell = row.find('td').eq(2);     // Third column is Name

            // Get values from each cell
            var categoryValue = $.trim(categoryCell.find('.label').text() || categoryCell.text());
            var actionValue = $.trim(actionCell.find('.label').text() || actionCell.text());
            var nameValue = $.trim(nameCell.find('.label').text() || nameCell.text());

            // Clean up values (remove any extra whitespace or newlines)
            categoryValue = categoryValue.replace(/\s+/g, ' ').trim();
            actionValue = actionValue.replace(/\s+/g, ' ').trim();
            nameValue = nameValue.replace(/\s+/g, ' ').trim();

            // Default to event name (most specific dimension)
            if (nameValue && nameValue !== '-') {
                dimensionType = 'name';
                eventValue = nameValue;
            } else if (actionValue && actionValue !== '-') {
                dimensionType = 'action';
                eventValue = actionValue;
            } else if (categoryValue && categoryValue !== '-') {
                dimensionType = 'category';
                eventValue = categoryValue;
            }
        } else {
            // For native Events reports, get the label from the first cell
            var labelCell = tr.find('td.label, td:first');
            if (labelCell.length) {
                var labelText = labelCell.find('.label').text() || labelCell.text();
                if (labelText) {
                    eventValue = $.trim(labelText);
                }
            }
        }

        // Get current site/period/date from various sources
        var idSite = broadcast.getValueFromHash('idSite') || piwik.idSite || broadcast.getValueFromUrl('idSite');
        var period = broadcast.getValueFromHash('period') || piwik.period || broadcast.getValueFromUrl('period') || 'day';
        var date = broadcast.getValueFromHash('date') || piwik.currentDateString || broadcast.getValueFromUrl('date') || 'today';

        // Build URL to EventsEnhanced detail page
        var hashParams = {
            idSite: idSite,
            period: period,
            date: date,
            category: 'General_Actions',
            subcategory: 'EventsEnhanced_EventsDetails',
            eventDimensionType: dimensionType,
            eventDimensionValue: eventValue
        };

        var newHash = $.param(hashParams);
        broadcast.propagateNewPage('#?' + newHash);
    };

    DataTable_RowActions_OpenEventDetails.prototype.doOpenPopover = function (parameter) {
        // Not using popover - just navigation
    };

    DataTable_RowActions_Registry.register({
        name: actionName,

        dataTableIcon: 'icon-show',

        order: 25,

        dataTableIconTooltip: [
            _pk_translate('EventsEnhanced_EventDetailsRowActionTitle'),
            _pk_translate('EventsEnhanced_EventDetailsRowActionDescription')
        ],

        isAvailableOnReport: function (dataTableParams) {
            if (!dataTableParams || !dataTableParams.module) {
                return false;
            }

            var reportKey = dataTableParams.module + '.' + dataTableParams.action;

            // Available on native Events reports and EventsEnhanced 3-dimensions report
            return reportToDimensionType.hasOwnProperty(reportKey);
        },

        isAvailableOnRow: function (dataTableParams, tr) {
            // Show on all rows in supported reports
            return true;
        },

        createInstance: function (dataTable, param) {
            return new DataTable_RowActions_OpenEventDetails(dataTable);
        }

    });
})();
