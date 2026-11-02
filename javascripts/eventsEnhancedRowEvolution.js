/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

/**
 * The tables of the event detail page are scoped to one event through string parameters. The core row evolution
 * only forwards numeric id* parameters and drops the unknown ones from the popover URL, the chart would lose the
 * event. The core action is decorated, not replaced, and the other reports are left untouched.
 */
(function () {
    var modules = ['EventsEnhanced', 'EventsEnhancedPremium'];
    var parameters = ['eventCategory', 'eventAction', 'eventName', 'dimensionType', 'dimensionValue'];
    var RowEvolution = DataTable_RowActions_RowEvolution;

    if (RowEvolution.allowedExtraParamKeys) {
        Array.prototype.push.apply(RowEvolution.allowedExtraParamKeys, parameters);
    }

    var performAction = RowEvolution.prototype.performAction;

    RowEvolution.prototype.performAction = function (label, tr, e, originalRow) {
        var tableParameters = this.dataTable && this.dataTable.param;

        if (tableParameters && modules.indexOf(tableParameters.module) !== -1) {
            var row = $(originalRow || tr);
            var override = $.extend({}, row.data('param-override'));

            parameters.forEach(function (name) {
                if (tableParameters[name] !== undefined) {
                    override[name] = tableParameters[name];
                }
            });

            row.data('param-override', override);
        }

        return performAction.apply(this, arguments);
    };
})();
