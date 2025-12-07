/*!
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

export interface Option {
  key: string;
  value: string;
}

export interface CustomDimension {
  id: number;
  name: string;
}

export interface BaseWidgetParams {
  eventDimensionType: string;
  eventDimensionValue: string;
}

export const DIMENSION_TYPES = {
  category: 'Events_EventCategory',
  action: 'Events_EventAction',
  name: 'Events_EventName',
} as const;
