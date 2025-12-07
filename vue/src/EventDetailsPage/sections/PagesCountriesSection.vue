<template>
  <div class="row">
    <div class="col s12 m6">
      <GoPremiumWidget :title="translate('Actions_PageUrls')" image="page-url" />
    </div>
    <div class="col s12 m6">
      <GoPremiumWidget :title="translate('UserCountry_Country')" image="country" />
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { translate } from 'CoreHome';
import GoPremiumWidget from '../GoPremiumWidget.vue';

export default defineComponent({
  components: { GoPremiumWidget },
  methods: {
    translate,
  },
});
</script>
