<template>
  <div>
    <div class="row">
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
import { translate } from 'CoreHome';
import {
  CustomDimension,
} from '../../types';
import GoPremiumWidget from '../GoPremiumWidget.vue';

export default defineComponent({
  components: { GoPremiumWidget },
  methods: {
    translate,
  },
  props: {
    customDimensions: {
      type: Array as PropType<CustomDimension[]>,
      default: () => [],
    },
  },
});
</script>
