<template>
  <div class="card">
    <div class="card-content omeh-card-content">
      <h2 class="card-title omeh-card-title">
        <div class="title" tabindex="6">{{ title }}</div>
        <div class="omeh-badge-wrapper">
          <span class="omeh-badge">Premium</span>
        </div>
      </h2>
      <slot>
        <div class="omeh-premium-report-image-wrapper">
          <img class="omeh-premium-report-image"
               :src="`/plugins/EventsEnhanced/images/${image}.jpg`"
               alt="Blurred premium report"
               :width="imageWidth"
               :height="imageHeight"
               loading="lazy"
          >
          <div class="omeh-premium-link-wrapper">
            <div class="omeh-premium-link">
              <p>
                <i class="icon-locked icon"></i>
                <a :href="link"
                   target="_blank"
                   rel="nofollow noopener"
                   class="omeh-link">{{ translate('EventsEnhanced_PremiumLinkText') }}</a>
                {{ translate('EventsEnhanced_PremiumText') }} {{ title }}
              </p>
            </div>
          </div>
        </div>
      </slot>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  props: {
    title: {
      type: String,
      default: 'Go premium',
    },
    link: {
      type: String,
      default: 'https://openmost.io/products/events-enhanced-pro?utm_source=matomo_installed_plugin&utm_medium=plugin_events_enhanced&utm_campaign=plugin_premium_events_enhanced',
    },
    image: {
      type: String,
      default: 'report',
    },
    imageWidth: {
      type: Number,
      default: 1203,
    },
    imageHeight: {
      type: Number,
      default: 846,
    },
  },
});
</script>

<style>
.omeh-card-content {
  padding-bottom: 0 !important;
}

.omeh-card-title {
  display: flex !important;
  justify-content: flex-start;
  gap: .5rem;
  flex-wrap: wrap;
}

.omeh-badge-wrapper {
  line-height: normal;
  display: inline-block;

  .omeh-badge {
    display: inline-block;
    padding: 2px 8px;
    background-color: #FFA32A;
    font-size: 12px;
    color: #000;
    border-radius: 1rem;
    line-height: normal;
  }
}

.omeh-premium-report-image-wrapper {
  position: relative;
  margin-left: -20px;
  margin-right: -20px;

  .omeh-premium-report-image {
    background-color: #CCCCCC;
    display: block;
    width: 100%;
    min-height: 200px;
    height: auto;
  }
}

.omeh-premium-link-wrapper {
  position: absolute;
  inset: 0;
  min-height: 100%;
  height: 100%;
  width: 100ù;
  display: flex;
  justify-content: center;
  align-content: center;
  align-items: center;
  padding: 20px;

  .omeh-premium-link {
    position: relative;
    border-radius: 8px;
    background: #FFF;
    box-shadow: 2px 2px 6px 0 rgba(0, 0, 0, 0.05);
    padding: 1rem;
    text-align: center;
    transition: .2s ease transform, .2s ease box-shadow;
    transform-origin: center center;

    &:hover {
      box-shadow: 2px 2px 6px 0 rgba(255, 163, 42, 0.1);
      transform: scale(1.03);
    }

    .icon-locked {
      color: #FFA32A;
      margin-right: .5rem;
    }

    p {
      padding-bottom: 0 !important;
      margin-bottom: 0 !important;

      .omeh-link {
        color: inherit;
        transition: .2s ease color;

        &:after {
          content: '';
          width: 100%;
          height: 100%;
          inset: 0;
          display: block;
          position: absolute;
        }
      }
    }
  }
}
</style>
