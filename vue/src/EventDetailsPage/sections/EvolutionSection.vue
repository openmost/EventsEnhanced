<template>
  <div class="row">
    <div class="col s12">
      <WidgetLoader
        :widget-params="widgetParams"
        :widget-name="translate('EventsEnhanced_EventsOverTime')"
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
  },
  computed: {
    widgetParams() {
      return {
        ...this.baseParams,
        module: 'EventsEnhanced',
        action: 'getEvolutionGraph',
      };
    },
  },
  methods: {
    translate,
  },
});
</script>
