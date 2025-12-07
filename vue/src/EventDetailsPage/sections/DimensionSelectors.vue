<template>
  <div class="eventsEnhanced-selectors" style="margin-bottom: 20px;">
    <div class="row">
      <div class="col s12 m3">
        <Field
          uicontrol="select"
          name="dimensionType"
          :model-value="selectedType"
          :title="translate('EventsEnhanced_EventDimension')"
          :full-width="true"
          :options="typeOptions"
          @update:model-value="onTypeChange"
        />
      </div>
      <div class="col s12 m3">
        <Field
          uicontrol="select"
          name="dimensionValue"
          :model-value="selectedValue"
          :title="translate('Events_EventValue')"
          :full-width="true"
          :disabled="isLoadingValues"
          :options="valueOptions"
          @update:model-value="onValueChange"
        />
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { translate } from 'CoreHome';
import { Field } from 'CorePluginsAdmin';
import {
  Option,
  DIMENSION_TYPES,
} from '../../types';

export default defineComponent({
  components: {
    Field,
  },
  props: {
    selectedType: {
      type: String,
      required: true,
    },
    selectedValue: {
      type: String,
      required: true,
    },
    valueOptions: {
      type: Array as PropType<Option[]>,
      default: () => [],
    },
    isLoadingValues: {
      type: Boolean,
      default: false,
    },
  },
  emits: ['update:selectedType', 'update:selectedValue'],
  computed: {
    typeOptions(): Option[] {
      return Object.entries(DIMENSION_TYPES).map(([key, translationKey]) => ({
        key,
        value: translate(translationKey),
      }));
    },
  },
  methods: {
    translate,
    onTypeChange(newType: string) {
      this.$emit('update:selectedType', newType);
    },
    onValueChange(newValue: string) {
      this.$emit('update:selectedValue', newValue);
    },
  },
});
</script>
