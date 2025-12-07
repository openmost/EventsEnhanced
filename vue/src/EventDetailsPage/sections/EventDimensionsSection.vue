<template>
  <div class="row">
    <div class="col s12 m4">
      <WidgetLoader
        :widget-params="relatedReport1Params"
        :widget-name="relatedReport1Name"
      />
    </div>
    <div class="col s12 m4">
      <WidgetLoader
        :widget-params="relatedReport2Params"
        :widget-name="relatedReport2Name"
      />
    </div>
    <div class="col s12 m4">
      <WidgetLoader
        :widget-params="eventValuesParams"
        :widget-name="translate('Events_EventValue')"
      />
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { translate, WidgetLoader } from 'CoreHome';
import { BaseWidgetParams } from '../../types';

export default defineComponent({
  components: {
    WidgetLoader,
  },
  props: {
    baseParams: {
      type: Object as PropType<BaseWidgetParams>,
      required: true,
    },
    dimensionType: {
      type: String,
      required: true,
    },
  },
  computed: {
    relatedReport1Params() {
      const actionMap: Record<string, string> = {
        category: 'getEventActionsForCategory',
        action: 'getEventCategoriesForAction',
        name: 'getEventCategoriesForName',
      };
      return {
        ...this.baseParams,
        module: 'EventsEnhanced',
        action: actionMap[this.dimensionType] || actionMap.name,
      };
    },
    relatedReport1Name(): string {
      const nameMap: Record<string, string> = {
        category: 'Events_EventActions',
        action: 'Events_EventCategories',
        name: 'Events_EventCategories',
      };
      return translate(nameMap[this.dimensionType] || nameMap.name);
    },
    relatedReport2Params() {
      const actionMap: Record<string, string> = {
        category: 'getEventNamesForCategory',
        action: 'getEventNamesForAction',
        name: 'getEventActionsForName',
      };
      return {
        ...this.baseParams,
        module: 'EventsEnhanced',
        action: actionMap[this.dimensionType] || actionMap.name,
      };
    },
    relatedReport2Name(): string {
      const nameMap: Record<string, string> = {
        category: 'Events_EventNames',
        action: 'Events_EventNames',
        name: 'Events_EventActions',
      };
      return translate(nameMap[this.dimensionType] || nameMap.name);
    },
    eventValuesParams() {
      const actionMap: Record<string, string> = {
        category: 'getEventValuesForCategory',
        action: 'getEventValuesForAction',
        name: 'getEventValuesForName',
      };
      return {
        ...this.baseParams,
        module: 'EventsEnhanced',
        action: actionMap[this.dimensionType] || actionMap.name,
      };
    },
  },
  methods: {
    translate,
  },
});
</script>
