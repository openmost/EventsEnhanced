<template>
  <div class="row">
    <div class="col s12 m6">
      <WidgetLoader
        v-if="premiumActivated"
        :widget-params="pageUrlsParams"
        :widget-name="translate('Actions_PageUrls')"
      />
      <GoPremiumWidget
        v-else
        :title="translate('Actions_PageUrls')"
        image="page-url"
        :image-height="886"
      />
    </div>
    <div class="col s12 m6">
      <WidgetLoader
        v-if="premiumActivated"
        :widget-params="countriesParams"
        :widget-name="translate('UserCountry_Country')"
      />
      <GoPremiumWidget
        v-else
        :title="translate('UserCountry_Country')"
        image="country"
      />
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { translate, WidgetLoader } from 'CoreHome';
import { BaseWidgetParams } from '../../types';
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
    premiumActivated: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    pageUrlsParams() {
      return {
        ...this.baseParams,
        module: 'EventsEnhancedPremium',
        action: 'getPageUrlsForEvent',
      };
    },
    countriesParams() {
      return {
        ...this.baseParams,
        module: 'EventsEnhancedPremium',
        action: 'getCountriesForEvent',
      };
    },
  },
  methods: {
    translate,
  },
});
</script>
