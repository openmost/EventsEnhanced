<template>
  <div class="eventsEnhanced-page">
    <!-- Dimension Selectors -->
    <DimensionSelectors
      :selected-type="selectedType"
      :selected-value="selectedValue"
      :value-options="computedValueOptions"
      :is-loading-values="isLoadingValues"
      @update:selected-type="onTypeChange"
      @update:selected-value="onValueChange"
    />

    <!-- Report Sections -->
    <div v-if="selectedValue" class="eventsEnhanced-reports">
      <EvolutionSection :base-params="baseWidgetParams" />
      <EventDimensionsSection
        :base-params="baseWidgetParams"
        :dimension-type="selectedType"
      />
      <PagesCountriesSection />
      <CustomDimensionsSection
        :custom-dimensions="customDimensions"
      />
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, nextTick } from 'vue';
import {
  translate,
  AjaxHelper,
  MatomoUrl,
} from 'CoreHome';
import {
  Option,
  CustomDimension,
  BaseWidgetParams,
} from '../types';
import DimensionSelectors from './sections/DimensionSelectors.vue';
import EvolutionSection from './sections/EvolutionSection.vue';
import EventDimensionsSection from './sections/EventDimensionsSection.vue';
import PagesCountriesSection from './sections/PagesCountriesSection.vue';
import CustomDimensionsSection from './sections/CustomDimensionsSection.vue';

export default defineComponent({
  components: {
    DimensionSelectors,
    EvolutionSection,
    EventDimensionsSection,
    PagesCountriesSection,
    CustomDimensionsSection,
  },
  props: {
    initialDimensionType: {
      type: String,
      default: 'name',
    },
    initialDimensionValue: {
      type: String,
      default: '',
    },
    dimensionValues: {
      type: Array as () => Option[],
      default: () => [],
    },
    customDimensions: {
      type: Array as () => CustomDimension[],
      default: () => [],
    },
  },
  data() {
    return {
      // Always use URL parameter values as initial state
      selectedType: this.initialDimensionType || 'name',
      selectedValue: this.initialDimensionValue || '',
      loadedValueOptions: [] as Option[],
      isLoadingValues: false,
      isInitializing: true,
      hasFetchedValues: false,
    };
  },
  computed: {
    baseWidgetParams(): BaseWidgetParams {
      return {
        eventDimensionType: this.selectedType,
        eventDimensionValue: this.selectedValue,
      };
    },
    // Compute value options ensuring the URL value is always available
    computedValueOptions(): Option[] {
      const options = [...this.loadedValueOptions];

      // If we have a selected value from URL but it's not in the loaded options,
      // add it so the selector shows the correct value even when no data for this period
      if (this.selectedValue) {
        const exists = options.some((opt) => opt.key === this.selectedValue);
        if (!exists) {
          // Insert at the beginning
          options.unshift({
            key: this.selectedValue,
            value: this.selectedValue,
          });
        }
      }

      return options;
    },
  },
  mounted() {
    // Initialize with values passed from PHP (if any)
    if (this.dimensionValues && this.dimensionValues.length > 0) {
      this.loadedValueOptions = [...this.dimensionValues];
      this.hasFetchedValues = true;
    }

    nextTick(() => {
      this.isInitializing = false;

      // Defer loading of dimension values if we don't have them yet
      // This improves initial page load as selectors show URL values immediately
      if (!this.hasFetchedValues) {
        this.fetchDimensionValuesDeferred();
      }
    });
  },
  methods: {
    translate,
    onTypeChange(newType: string) {
      if (newType !== this.selectedType && !this.isInitializing) {
        this.selectedType = newType;
        // When type changes, fetch new values but preserve current value initially
        this.fetchDimensionValuesAndUpdateUrl(true);
      }
    },
    onValueChange(newValue: string) {
      if (newValue && newValue !== this.selectedValue && !this.isInitializing) {
        this.selectedValue = newValue;
        this.updateUrl();
      }
    },
    updateUrl() {
      MatomoUrl.updateHash({
        ...MatomoUrl.hashParsed.value,
        category: 'General_Actions',
        subcategory: 'EventsEnhanced_EventsDetails',
        eventDimensionType: this.selectedType,
        eventDimensionValue: this.selectedValue,
      });
    },
    // Deferred fetch - loads values in background without blocking UI
    fetchDimensionValuesDeferred() {
      this.isLoadingValues = true;

      AjaxHelper.fetch<Option[]>({
        module: 'EventsEnhanced',
        action: 'getDimensionValues',
        dimensionType: this.selectedType,
      })
        .then((values: Option[]) => {
          this.isLoadingValues = false;
          this.loadedValueOptions = values || [];
          this.hasFetchedValues = true;
          // Don't change selectedValue - keep the URL value
        })
        .catch(() => {
          this.isLoadingValues = false;
          this.loadedValueOptions = [];
          this.hasFetchedValues = true;
        });
    },
    // Fetch when type changes - may update selected value
    fetchDimensionValuesAndUpdateUrl(preserveCurrentValue = false) {
      this.isLoadingValues = true;
      const currentValue = this.selectedValue;

      AjaxHelper.fetch<Option[]>({
        module: 'EventsEnhanced',
        action: 'getDimensionValues',
        dimensionType: this.selectedType,
      })
        .then((values: Option[]) => {
          this.isLoadingValues = false;
          this.loadedValueOptions = values || [];
          this.hasFetchedValues = true;

          if (preserveCurrentValue && currentValue) {
            // Check if current value exists in new options
            const exists = this.loadedValueOptions.some(
              (opt) => opt.key === currentValue,
            );
            if (exists) {
              // Keep current value
              this.selectedValue = currentValue;
            } else if (this.loadedValueOptions.length > 0) {
              // Current value doesn't exist for this dimension type, select first
              this.selectedValue = this.loadedValueOptions[0].key;
            } else {
              // No values available, clear selection
              this.selectedValue = '';
            }
          } else if (this.loadedValueOptions.length > 0) {
            this.selectedValue = this.loadedValueOptions[0].key;
          } else {
            this.selectedValue = '';
          }

          this.updateUrl();
        })
        .catch(() => {
          this.isLoadingValues = false;
          this.loadedValueOptions = [];
          this.hasFetchedValues = true;
        });
    },
  },
});
</script>
