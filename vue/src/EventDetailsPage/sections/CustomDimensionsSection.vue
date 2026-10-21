<template>
  <div>
    <template v-if="premiumActivated">
      <div v-if="dimensionsWithData.length > 0" class="row">
        <div class="col s12 m6">
          <!-- Custom dimensions (even indices) -->
          <template v-for="(dim, index) in dimensionsWithData" :key="'cd-even-' + dim.id">
            <WidgetLoader
              v-if="index % 2 === 0"
              :widget-params="getWidgetParams(dim.id)"
              :widget-name="dim.name"
            />
          </template>
        </div>
        <div class="col s12 m6">
          <!-- Custom dimensions (odd indices) -->
          <template v-for="(dim, index) in dimensionsWithData" :key="'cd-odd-' + dim.id">
            <WidgetLoader
              v-if="index % 2 === 1"
              :widget-params="getWidgetParams(dim.id)"
              :widget-name="dim.name"
            />
          </template>
        </div>
      </div>

      <div v-if="emptyDimensions.length > 0" class="eventsEnhanced-empty-dimensions">
        <p class="text-muted" style="font-style: italic; margin-top: 20px;">
          {{ emptyDimensionsMessage }}
        </p>
      </div>
    </template>

    <div v-else class="row">
      <template v-if="customDimensions.length !== 0">
        <div class="col s12 m6">
          <!-- Custom dimensions (even indices) -->
          <template v-for="(dim, index) in customDimensions" :key="'cd-even-' + dim.id">
            <GoPremiumWidget :title="dim.name" v-if="index % 2 === 0" image="custom-dimension" />
          </template>
        </div>
        <div class="col s12 m6">
          <!-- Custom dimensions (odd indices) -->
          <template v-for="(dim, index) in customDimensions" :key="'cd-odd-' + dim.id">
            <GoPremiumWidget :title="dim.name" v-if="index % 2 === 1" image="custom-dimension" />
          </template>
        </div>
      </template>
      <template v-else>
        <div class="col s12 m6">
          <GoPremiumWidget
            :title="translate('CustomDimensions_CustomDimensionId', '1')"
            image="custom-dimension"
          />
          <GoPremiumWidget
            :title="translate('CustomDimensions_CustomDimensionId', '3')"
            image="custom-dimension"
          />
        </div>
        <div class="col s12 m6">
          <GoPremiumWidget
            :title="translate('CustomDimensions_CustomDimensionId', '2')"
            image="custom-dimension"
          />
          <GoPremiumWidget
            :title="translate('CustomDimensions_CustomDimensionId', '4')"
            image="custom-dimension"
          />
        </div>
      </template>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import {
  translate,
  AjaxHelper,
  MatomoUrl,
  WidgetLoader,
} from 'CoreHome';
import {
  BaseWidgetParams,
  CustomDimension,
} from '../../types';
import GoPremiumWidget from '../GoPremiumWidget.vue';

export default defineComponent({
  components: {
    GoPremiumWidget,
    WidgetLoader,
  },
  props: {
    baseParams: {
      type: Object as PropType<BaseWidgetParams>,
      required: true,
    },
    customDimensions: {
      type: Array as PropType<CustomDimension[]>,
      default: () => [],
    },
    premiumActivated: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      dataStatus: {} as Record<number, boolean>,
      isChecking: false,
    };
  },
  computed: {
    dimensionsWithData(): CustomDimension[] {
      return this.customDimensions.filter(
        (dim) => this.dataStatus[dim.id] === true,
      );
    },
    emptyDimensions(): CustomDimension[] {
      const checkedCount = Object.keys(this.dataStatus).length;
      if (checkedCount === 0) {
        return [];
      }
      return this.customDimensions.filter(
        (dim) => this.dataStatus[dim.id] === false,
      );
    },
    emptyDimensionsMessage(): string {
      const emptyDims = this.emptyDimensions;
      if (emptyDims.length === 0) {
        return '';
      }

      const names = emptyDims.map((dim) => `"${dim.name}"`);

      if (names.length === 1) {
        return translate('EventsEnhanced_DimensionNoData', names[0]);
      }

      const lastItem = names.pop();
      const formattedNames = `${names.join(', ')} ${translate('General_And')} ${lastItem}`;
      return translate('EventsEnhanced_DimensionsNoData', formattedNames);
    },
  },
  watch: {
    baseParams: {
      handler() {
        this.checkDimensionsData();
      },
      deep: true,
      immediate: true,
    },
  },
  methods: {
    translate,
    getWidgetParams(idDimension: number) {
      return {
        ...this.baseParams,
        module: 'EventsEnhancedPremium',
        action: 'getCustomDimensionForEvent',
        idDimension,
      };
    },
    // Custom dimensions without data for the selected event are listed in a sentence
    // instead of empty widgets
    checkDimensionsData() {
      if (!this.premiumActivated || this.customDimensions.length === 0
        || !this.baseParams.eventDimensionValue
      ) {
        return;
      }

      this.isChecking = true;
      this.dataStatus = {};

      const hashParams = MatomoUrl.hashParsed.value;
      const period = hashParams.period || 'day';
      const date = hashParams.date || 'today';
      const idSite = hashParams.idSite || '1';

      const promises = this.customDimensions.map((dim) => AjaxHelper.fetch({
        module: 'API',
        method: 'EventsEnhancedPremium.getCustomDimensionForEvent',
        idSite,
        period,
        date,
        dimensionType: this.baseParams.eventDimensionType,
        dimensionValue: this.baseParams.eventDimensionValue,
        idDimension: dim.id,
        format: 'JSON',
        filter_limit: 1,
      }).then((response: unknown[]) => {
        const hasData = Array.isArray(response) && response.length > 0;
        this.dataStatus = {
          ...this.dataStatus,
          [dim.id]: hasData,
        };
      }).catch(() => {
        this.dataStatus = {
          ...this.dataStatus,
          [dim.id]: false,
        };
      }));

      Promise.all(promises).finally(() => {
        this.isChecking = false;
      });
    },
  },
});
</script>
