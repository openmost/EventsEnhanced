module.exports =
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "plugins/EventsEnhanced/vue/dist/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "fae3");
/******/ })
/************************************************************************/
/******/ ({

/***/ "1797":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_vue_cli_service_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_cli_service_node_modules_vue_loader_v16_dist_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_vue_cli_service_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_cli_service_node_modules_vue_loader_v16_dist_index_js_ref_1_1_GoPremiumWidget_vue_vue_type_style_index_0_id_078ea386_lang_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("d488");
/* harmony import */ var _node_modules_vue_cli_service_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_cli_service_node_modules_vue_loader_v16_dist_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_vue_cli_service_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_cli_service_node_modules_vue_loader_v16_dist_index_js_ref_1_1_GoPremiumWidget_vue_vue_type_style_index_0_id_078ea386_lang_css__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_vue_cli_service_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_cli_service_node_modules_vue_loader_v16_dist_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_vue_cli_service_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_cli_service_node_modules_vue_loader_v16_dist_index_js_ref_1_1_GoPremiumWidget_vue_vue_type_style_index_0_id_078ea386_lang_css__WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "19dc":
/***/ (function(module, exports) {

module.exports = require("CoreHome");

/***/ }),

/***/ "8bbf":
/***/ (function(module, exports) {

module.exports = require("vue");

/***/ }),

/***/ "a5a2":
/***/ (function(module, exports) {

module.exports = require("CorePluginsAdmin");

/***/ }),

/***/ "d488":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "fae3":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, "EventDetailsPage", function() { return /* reexport */ EventDetailsPage; });

// CONCATENATED MODULE: ./node_modules/@vue/cli-service/lib/commands/build/setPublicPath.js
// This file is imported into lib/wc client bundles.

if (typeof window !== 'undefined') {
  var currentScript = window.document.currentScript
  if (false) { var getCurrentScript; }

  var src = currentScript && currentScript.src.match(/(.+\/)[^/]+\.js(\?.*)?$/)
  if (src) {
    __webpack_require__.p = src[1] // eslint-disable-line
  }
}

// Indicate to webpack that this file can be concatenated
/* harmony default export */ var setPublicPath = (null);

// EXTERNAL MODULE: external {"commonjs":"vue","commonjs2":"vue","root":"Vue"}
var external_commonjs_vue_commonjs2_vue_root_Vue_ = __webpack_require__("8bbf");

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/EventsEnhanced/vue/src/EventDetailsPage/EventDetailsPage.vue?vue&type=template&id=d8984216

const _hoisted_1 = {
  class: "eventsEnhanced-page"
};
const _hoisted_2 = {
  key: 0,
  class: "eventsEnhanced-reports"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_DimensionSelectors = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("DimensionSelectors");
  const _component_EvolutionSection = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("EvolutionSection");
  const _component_EventDimensionsSection = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("EventDimensionsSection");
  const _component_PagesCountriesSection = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("PagesCountriesSection");
  const _component_CustomDimensionsSection = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("CustomDimensionsSection");
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", _hoisted_1, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_DimensionSelectors, {
    "selected-type": _ctx.selectedType,
    "selected-value": _ctx.selectedValue,
    "value-options": _ctx.computedValueOptions,
    "is-loading-values": _ctx.isLoadingValues,
    "onUpdate:selectedType": _ctx.onTypeChange,
    "onUpdate:selectedValue": _ctx.onValueChange
  }, null, 8, ["selected-type", "selected-value", "value-options", "is-loading-values", "onUpdate:selectedType", "onUpdate:selectedValue"]), _ctx.selectedValue ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", _hoisted_2, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_EvolutionSection, {
    "base-params": _ctx.baseWidgetParams
  }, null, 8, ["base-params"]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_EventDimensionsSection, {
    "base-params": _ctx.baseWidgetParams,
    "dimension-type": _ctx.selectedType
  }, null, 8, ["base-params", "dimension-type"]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_PagesCountriesSection), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_CustomDimensionsSection, {
    "custom-dimensions": _ctx.customDimensions
  }, null, 8, ["custom-dimensions"])])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]);
}
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/EventDetailsPage.vue?vue&type=template&id=d8984216

// EXTERNAL MODULE: external "CoreHome"
var external_CoreHome_ = __webpack_require__("19dc");

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/DimensionSelectors.vue?vue&type=template&id=4979084a

const DimensionSelectorsvue_type_template_id_4979084a_hoisted_1 = {
  class: "eventsEnhanced-selectors"
};
const DimensionSelectorsvue_type_template_id_4979084a_hoisted_2 = {
  class: "row"
};
const _hoisted_3 = {
  class: "col s12 m3"
};
const _hoisted_4 = {
  class: "col s12 m3"
};
function DimensionSelectorsvue_type_template_id_4979084a_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_Field = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("Field");
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", DimensionSelectorsvue_type_template_id_4979084a_hoisted_1, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", DimensionSelectorsvue_type_template_id_4979084a_hoisted_2, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_3, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_Field, {
    uicontrol: "select",
    name: "dimensionType",
    "model-value": _ctx.selectedType,
    title: _ctx.translate('EventsEnhanced_EventDimension'),
    "full-width": true,
    options: _ctx.typeOptions,
    "onUpdate:modelValue": _ctx.onTypeChange
  }, null, 8, ["model-value", "title", "options", "onUpdate:modelValue"])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_4, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_Field, {
    uicontrol: "select",
    name: "dimensionValue",
    "model-value": _ctx.selectedValue,
    title: _ctx.translate('Events_EventValue'),
    "full-width": true,
    disabled: _ctx.isLoadingValues,
    options: _ctx.valueOptions,
    "onUpdate:modelValue": _ctx.onValueChange
  }, null, 8, ["model-value", "title", "disabled", "options", "onUpdate:modelValue"])])])]);
}
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/DimensionSelectors.vue?vue&type=template&id=4979084a

// EXTERNAL MODULE: external "CorePluginsAdmin"
var external_CorePluginsAdmin_ = __webpack_require__("a5a2");

// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/types.ts
/*!
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
const DIMENSION_TYPES = {
  category: 'Events_EventCategory',
  action: 'Events_EventAction',
  name: 'Events_EventName'
};
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/DimensionSelectors.vue?vue&type=script&lang=ts




/* harmony default export */ var DimensionSelectorsvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  components: {
    Field: external_CorePluginsAdmin_["Field"]
  },
  props: {
    selectedType: {
      type: String,
      required: true
    },
    selectedValue: {
      type: String,
      required: true
    },
    valueOptions: {
      type: Array,
      default: () => []
    },
    isLoadingValues: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:selectedType', 'update:selectedValue'],
  computed: {
    typeOptions() {
      return Object.entries(DIMENSION_TYPES).map(([key, translationKey]) => ({
        key,
        value: Object(external_CoreHome_["translate"])(translationKey)
      }));
    }
  },
  methods: {
    translate: external_CoreHome_["translate"],
    onTypeChange(newType) {
      this.$emit('update:selectedType', newType);
    },
    onValueChange(newValue) {
      this.$emit('update:selectedValue', newValue);
    }
  }
}));
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/DimensionSelectors.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/DimensionSelectors.vue



DimensionSelectorsvue_type_script_lang_ts.render = DimensionSelectorsvue_type_template_id_4979084a_render

/* harmony default export */ var DimensionSelectors = (DimensionSelectorsvue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/EvolutionSection.vue?vue&type=template&id=0d67f3b1

const EvolutionSectionvue_type_template_id_0d67f3b1_hoisted_1 = {
  class: "row"
};
const EvolutionSectionvue_type_template_id_0d67f3b1_hoisted_2 = {
  class: "col s12"
};
function EvolutionSectionvue_type_template_id_0d67f3b1_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_WidgetLoader = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("WidgetLoader");
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", EvolutionSectionvue_type_template_id_0d67f3b1_hoisted_1, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", EvolutionSectionvue_type_template_id_0d67f3b1_hoisted_2, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_WidgetLoader, {
    "widget-params": _ctx.widgetParams,
    "widget-name": _ctx.translate('EventsEnhanced_EventsOverTime')
  }, null, 8, ["widget-params", "widget-name"])])]);
}
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/EvolutionSection.vue?vue&type=template&id=0d67f3b1

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/EvolutionSection.vue?vue&type=script&lang=ts


/* harmony default export */ var EvolutionSectionvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  components: {
    WidgetLoader: external_CoreHome_["WidgetLoader"]
  },
  props: {
    baseParams: {
      type: Object,
      required: true
    }
  },
  computed: {
    widgetParams() {
      return Object.assign(Object.assign({}, this.baseParams), {}, {
        module: 'EventsEnhanced',
        action: 'getEvolutionGraph'
      });
    }
  },
  methods: {
    translate: external_CoreHome_["translate"]
  }
}));
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/EvolutionSection.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/EvolutionSection.vue



EvolutionSectionvue_type_script_lang_ts.render = EvolutionSectionvue_type_template_id_0d67f3b1_render

/* harmony default export */ var EvolutionSection = (EvolutionSectionvue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/EventDimensionsSection.vue?vue&type=template&id=590fd0c0

const EventDimensionsSectionvue_type_template_id_590fd0c0_hoisted_1 = {
  class: "row"
};
const EventDimensionsSectionvue_type_template_id_590fd0c0_hoisted_2 = {
  class: "col s12 m4"
};
const EventDimensionsSectionvue_type_template_id_590fd0c0_hoisted_3 = {
  class: "col s12 m4"
};
const EventDimensionsSectionvue_type_template_id_590fd0c0_hoisted_4 = {
  class: "col s12 m4"
};
function EventDimensionsSectionvue_type_template_id_590fd0c0_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_WidgetLoader = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("WidgetLoader");
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", EventDimensionsSectionvue_type_template_id_590fd0c0_hoisted_1, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", EventDimensionsSectionvue_type_template_id_590fd0c0_hoisted_2, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_WidgetLoader, {
    "widget-params": _ctx.relatedReport1Params,
    "widget-name": _ctx.relatedReport1Name
  }, null, 8, ["widget-params", "widget-name"])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", EventDimensionsSectionvue_type_template_id_590fd0c0_hoisted_3, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_WidgetLoader, {
    "widget-params": _ctx.relatedReport2Params,
    "widget-name": _ctx.relatedReport2Name
  }, null, 8, ["widget-params", "widget-name"])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", EventDimensionsSectionvue_type_template_id_590fd0c0_hoisted_4, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_WidgetLoader, {
    "widget-params": _ctx.eventValuesParams,
    "widget-name": _ctx.translate('Events_EventValue')
  }, null, 8, ["widget-params", "widget-name"])])]);
}
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/EventDimensionsSection.vue?vue&type=template&id=590fd0c0

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/EventDimensionsSection.vue?vue&type=script&lang=ts


/* harmony default export */ var EventDimensionsSectionvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  components: {
    WidgetLoader: external_CoreHome_["WidgetLoader"]
  },
  props: {
    baseParams: {
      type: Object,
      required: true
    },
    dimensionType: {
      type: String,
      required: true
    }
  },
  computed: {
    relatedReport1Params() {
      const actionMap = {
        category: 'getEventActionsForCategory',
        action: 'getEventCategoriesForAction',
        name: 'getEventCategoriesForName'
      };
      return Object.assign(Object.assign({}, this.baseParams), {}, {
        module: 'EventsEnhanced',
        action: actionMap[this.dimensionType] || actionMap.name
      });
    },
    relatedReport1Name() {
      const nameMap = {
        category: 'Events_EventActions',
        action: 'Events_EventCategories',
        name: 'Events_EventCategories'
      };
      return Object(external_CoreHome_["translate"])(nameMap[this.dimensionType] || nameMap.name);
    },
    relatedReport2Params() {
      const actionMap = {
        category: 'getEventNamesForCategory',
        action: 'getEventNamesForAction',
        name: 'getEventActionsForName'
      };
      return Object.assign(Object.assign({}, this.baseParams), {}, {
        module: 'EventsEnhanced',
        action: actionMap[this.dimensionType] || actionMap.name
      });
    },
    relatedReport2Name() {
      const nameMap = {
        category: 'Events_EventNames',
        action: 'Events_EventNames',
        name: 'Events_EventActions'
      };
      return Object(external_CoreHome_["translate"])(nameMap[this.dimensionType] || nameMap.name);
    },
    eventValuesParams() {
      const actionMap = {
        category: 'getEventValuesForCategory',
        action: 'getEventValuesForAction',
        name: 'getEventValuesForName'
      };
      return Object.assign(Object.assign({}, this.baseParams), {}, {
        module: 'EventsEnhanced',
        action: actionMap[this.dimensionType] || actionMap.name
      });
    }
  },
  methods: {
    translate: external_CoreHome_["translate"]
  }
}));
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/EventDimensionsSection.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/EventDimensionsSection.vue



EventDimensionsSectionvue_type_script_lang_ts.render = EventDimensionsSectionvue_type_template_id_590fd0c0_render

/* harmony default export */ var EventDimensionsSection = (EventDimensionsSectionvue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/PagesCountriesSection.vue?vue&type=template&id=6036bb9c

const PagesCountriesSectionvue_type_template_id_6036bb9c_hoisted_1 = {
  class: "row"
};
const PagesCountriesSectionvue_type_template_id_6036bb9c_hoisted_2 = {
  class: "col s12 m6"
};
const PagesCountriesSectionvue_type_template_id_6036bb9c_hoisted_3 = {
  class: "col s12 m6"
};
function PagesCountriesSectionvue_type_template_id_6036bb9c_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_GoPremiumWidget = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("GoPremiumWidget");
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", PagesCountriesSectionvue_type_template_id_6036bb9c_hoisted_1, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", PagesCountriesSectionvue_type_template_id_6036bb9c_hoisted_2, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_GoPremiumWidget, {
    title: _ctx.translate('Actions_PageUrls'),
    image: "page-url",
    "image-height": 886
  }, null, 8, ["title"])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", PagesCountriesSectionvue_type_template_id_6036bb9c_hoisted_3, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_GoPremiumWidget, {
    title: _ctx.translate('UserCountry_Country'),
    image: "country"
  }, null, 8, ["title"])])]);
}
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/PagesCountriesSection.vue?vue&type=template&id=6036bb9c

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/EventsEnhanced/vue/src/EventDetailsPage/GoPremiumWidget.vue?vue&type=template&id=078ea386

const GoPremiumWidgetvue_type_template_id_078ea386_hoisted_1 = {
  class: "card"
};
const GoPremiumWidgetvue_type_template_id_078ea386_hoisted_2 = {
  class: "card-content omeh-card-content"
};
const GoPremiumWidgetvue_type_template_id_078ea386_hoisted_3 = {
  class: "card-title omeh-card-title"
};
const GoPremiumWidgetvue_type_template_id_078ea386_hoisted_4 = {
  class: "title"
};
const _hoisted_5 = /*#__PURE__*/Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", {
  class: "omeh-badge-wrapper"
}, [/*#__PURE__*/Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", {
  class: "omeh-badge"
}, "Premium")], -1);
const _hoisted_6 = {
  class: "omeh-premium-report-image-wrapper"
};
const _hoisted_7 = ["src", "width", "height"];
const _hoisted_8 = {
  class: "omeh-premium-link-wrapper"
};
const _hoisted_9 = {
  class: "omeh-premium-link"
};
const _hoisted_10 = /*#__PURE__*/Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("i", {
  class: "icon-locked icon"
}, null, -1);
const _hoisted_11 = ["href"];
function GoPremiumWidgetvue_type_template_id_078ea386_render(_ctx, _cache, $props, $setup, $data, $options) {
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", GoPremiumWidgetvue_type_template_id_078ea386_hoisted_1, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", GoPremiumWidgetvue_type_template_id_078ea386_hoisted_2, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("h2", GoPremiumWidgetvue_type_template_id_078ea386_hoisted_3, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", GoPremiumWidgetvue_type_template_id_078ea386_hoisted_4, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.title), 1), _hoisted_5]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderSlot"])(_ctx.$slots, "default", {}, () => [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_6, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("img", {
    class: "omeh-premium-report-image",
    src: `${_ctx.matomoBaseUrl}plugins/EventsEnhanced/images/${_ctx.image}.jpg`,
    alt: "Blurred premium report",
    width: _ctx.imageWidth,
    height: _ctx.imageHeight,
    loading: "lazy"
  }, null, 8, _hoisted_7), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_8, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_9, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", null, [_hoisted_10, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("a", {
    href: _ctx.link,
    target: "_blank",
    rel: "nofollow noopener",
    class: "omeh-link"
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('EventsEnhanced_PremiumLinkText')), 9, _hoisted_11), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('EventsEnhanced_PremiumText')) + " " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.title), 1)])])])])])])]);
}
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/GoPremiumWidget.vue?vue&type=template&id=078ea386

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/EventsEnhanced/vue/src/EventDetailsPage/GoPremiumWidget.vue?vue&type=script&lang=ts

/* harmony default export */ var GoPremiumWidgetvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  computed: {
    matomoBaseUrl() {
      var _window$Matomo;
      return ((_window$Matomo = window.Matomo) === null || _window$Matomo === void 0 ? void 0 : _window$Matomo.piwikUrl) || '/';
    }
  },
  props: {
    title: {
      type: String,
      default: 'Go premium'
    },
    link: {
      type: String,
      default: 'https://openmost.io/products/events-enhanced/?utm_source=matomo_installed_plugin&utm_medium=plugin_events_enhanced&utm_campaign=plugin_premium_events_enhanced'
    },
    image: {
      type: String,
      default: 'report'
    },
    imageWidth: {
      type: Number,
      default: 1203
    },
    imageHeight: {
      type: Number,
      default: 846
    }
  }
}));
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/GoPremiumWidget.vue?vue&type=script&lang=ts
 
// EXTERNAL MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/GoPremiumWidget.vue?vue&type=style&index=0&id=078ea386&lang=css
var GoPremiumWidgetvue_type_style_index_0_id_078ea386_lang_css = __webpack_require__("1797");

// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/GoPremiumWidget.vue





GoPremiumWidgetvue_type_script_lang_ts.render = GoPremiumWidgetvue_type_template_id_078ea386_render

/* harmony default export */ var GoPremiumWidget = (GoPremiumWidgetvue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/PagesCountriesSection.vue?vue&type=script&lang=ts



/* harmony default export */ var PagesCountriesSectionvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  components: {
    GoPremiumWidget: GoPremiumWidget
  },
  methods: {
    translate: external_CoreHome_["translate"]
  }
}));
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/PagesCountriesSection.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/PagesCountriesSection.vue



PagesCountriesSectionvue_type_script_lang_ts.render = PagesCountriesSectionvue_type_template_id_6036bb9c_render

/* harmony default export */ var PagesCountriesSection = (PagesCountriesSectionvue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/CustomDimensionsSection.vue?vue&type=template&id=0a25bfe2

const CustomDimensionsSectionvue_type_template_id_0a25bfe2_hoisted_1 = {
  class: "row"
};
const CustomDimensionsSectionvue_type_template_id_0a25bfe2_hoisted_2 = {
  class: "col s12 m6"
};
const CustomDimensionsSectionvue_type_template_id_0a25bfe2_hoisted_3 = {
  class: "col s12 m6"
};
const CustomDimensionsSectionvue_type_template_id_0a25bfe2_hoisted_4 = {
  class: "col s12 m6"
};
const CustomDimensionsSectionvue_type_template_id_0a25bfe2_hoisted_5 = {
  class: "col s12 m6"
};
function CustomDimensionsSectionvue_type_template_id_0a25bfe2_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_GoPremiumWidget = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("GoPremiumWidget");
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", CustomDimensionsSectionvue_type_template_id_0a25bfe2_hoisted_1, [_ctx.customDimensions.length !== 0 ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
    key: 0
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", CustomDimensionsSectionvue_type_template_id_0a25bfe2_hoisted_2, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.customDimensions, (dim, index) => {
    return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
      key: 'cd-even-' + dim.id
    }, [index % 2 === 0 ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_GoPremiumWidget, {
      key: 0,
      title: dim.name,
      image: "custom-dimension"
    }, null, 8, ["title"])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)], 64);
  }), 128))]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", CustomDimensionsSectionvue_type_template_id_0a25bfe2_hoisted_3, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.customDimensions, (dim, index) => {
    return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
      key: 'cd-odd-' + dim.id
    }, [index % 2 === 1 ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_GoPremiumWidget, {
      key: 0,
      title: dim.name,
      image: "custom-dimension"
    }, null, 8, ["title"])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)], 64);
  }), 128))])], 64)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
    key: 1
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", CustomDimensionsSectionvue_type_template_id_0a25bfe2_hoisted_4, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_GoPremiumWidget, {
    title: _ctx.translate('CustomDimensions_CustomDimensionId', '1'),
    image: "custom-dimension"
  }, null, 8, ["title"]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_GoPremiumWidget, {
    title: _ctx.translate('CustomDimensions_CustomDimensionId', '3'),
    image: "custom-dimension"
  }, null, 8, ["title"])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", CustomDimensionsSectionvue_type_template_id_0a25bfe2_hoisted_5, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_GoPremiumWidget, {
    title: _ctx.translate('CustomDimensions_CustomDimensionId', '2'),
    image: "custom-dimension"
  }, null, 8, ["title"]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_GoPremiumWidget, {
    title: _ctx.translate('CustomDimensions_CustomDimensionId', '4'),
    image: "custom-dimension"
  }, null, 8, ["title"])])], 64))])]);
}
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/CustomDimensionsSection.vue?vue&type=template&id=0a25bfe2

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/CustomDimensionsSection.vue?vue&type=script&lang=ts



/* harmony default export */ var CustomDimensionsSectionvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  components: {
    GoPremiumWidget: GoPremiumWidget
  },
  methods: {
    translate: external_CoreHome_["translate"]
  },
  props: {
    customDimensions: {
      type: Array,
      default: () => []
    }
  }
}));
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/CustomDimensionsSection.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/sections/CustomDimensionsSection.vue



CustomDimensionsSectionvue_type_script_lang_ts.render = CustomDimensionsSectionvue_type_template_id_0a25bfe2_render

/* harmony default export */ var CustomDimensionsSection = (CustomDimensionsSectionvue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/EventsEnhanced/vue/src/EventDetailsPage/EventDetailsPage.vue?vue&type=script&lang=ts







/* harmony default export */ var EventDetailsPagevue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  components: {
    DimensionSelectors: DimensionSelectors,
    EvolutionSection: EvolutionSection,
    EventDimensionsSection: EventDimensionsSection,
    PagesCountriesSection: PagesCountriesSection,
    CustomDimensionsSection: CustomDimensionsSection
  },
  props: {
    initialDimensionType: {
      type: String,
      default: 'name'
    },
    initialDimensionValue: {
      type: String,
      default: ''
    },
    dimensionValues: {
      type: Array,
      default: () => []
    },
    customDimensions: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      // Always use URL parameter values as initial state
      selectedType: this.initialDimensionType || 'name',
      selectedValue: this.initialDimensionValue || '',
      loadedValueOptions: [],
      isLoadingValues: false,
      isInitializing: true,
      hasFetchedValues: false
    };
  },
  computed: {
    baseWidgetParams() {
      return {
        eventDimensionType: this.selectedType,
        eventDimensionValue: this.selectedValue
      };
    },
    // Compute value options ensuring the URL value is always available
    computedValueOptions() {
      const options = [...this.loadedValueOptions];
      // If we have a selected value from URL but it's not in the loaded options,
      // add it so the selector shows the correct value even when no data for this period
      if (this.selectedValue) {
        const exists = options.some(opt => opt.key === this.selectedValue);
        if (!exists) {
          // Insert at the beginning
          options.unshift({
            key: this.selectedValue,
            value: this.selectedValue
          });
        }
      }
      return options;
    }
  },
  mounted() {
    // Initialize with values passed from PHP (if any)
    if (this.dimensionValues && this.dimensionValues.length > 0) {
      this.loadedValueOptions = [...this.dimensionValues];
      this.hasFetchedValues = true;
    }
    Object(external_commonjs_vue_commonjs2_vue_root_Vue_["nextTick"])(() => {
      this.isInitializing = false;
      // Defer loading of dimension values if we don't have them yet
      // This improves initial page load as selectors show URL values immediately
      if (!this.hasFetchedValues) {
        this.fetchDimensionValuesDeferred();
      }
    });
  },
  methods: {
    translate: external_CoreHome_["translate"],
    onTypeChange(newType) {
      if (newType !== this.selectedType && !this.isInitializing) {
        this.selectedType = newType;
        // When type changes, fetch new values but preserve current value initially
        this.fetchDimensionValuesAndUpdateUrl(true);
      }
    },
    onValueChange(newValue) {
      if (newValue && newValue !== this.selectedValue && !this.isInitializing) {
        this.selectedValue = newValue;
        this.updateUrl();
      }
    },
    updateUrl() {
      external_CoreHome_["MatomoUrl"].updateHash(Object.assign(Object.assign({}, external_CoreHome_["MatomoUrl"].hashParsed.value), {}, {
        category: 'General_Actions',
        subcategory: 'EventsEnhanced_EventsDetails',
        eventDimensionType: this.selectedType,
        eventDimensionValue: this.selectedValue
      }));
    },
    // Deferred fetch - loads values in background without blocking UI
    fetchDimensionValuesDeferred() {
      this.isLoadingValues = true;
      external_CoreHome_["AjaxHelper"].fetch({
        module: 'EventsEnhanced',
        action: 'getDimensionValues',
        dimensionType: this.selectedType
      }).then(values => {
        this.isLoadingValues = false;
        this.loadedValueOptions = values || [];
        this.hasFetchedValues = true;
        // Don't change selectedValue - keep the URL value
      }).catch(() => {
        this.isLoadingValues = false;
        this.loadedValueOptions = [];
        this.hasFetchedValues = true;
      });
    },
    // Fetch when type changes - may update selected value
    fetchDimensionValuesAndUpdateUrl(preserveCurrentValue = false) {
      this.isLoadingValues = true;
      const currentValue = this.selectedValue;
      external_CoreHome_["AjaxHelper"].fetch({
        module: 'EventsEnhanced',
        action: 'getDimensionValues',
        dimensionType: this.selectedType
      }).then(values => {
        this.isLoadingValues = false;
        this.loadedValueOptions = values || [];
        this.hasFetchedValues = true;
        if (preserveCurrentValue && currentValue) {
          // Check if current value exists in new options
          const exists = this.loadedValueOptions.some(opt => opt.key === currentValue);
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
      }).catch(() => {
        this.isLoadingValues = false;
        this.loadedValueOptions = [];
        this.hasFetchedValues = true;
      });
    }
  }
}));
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/EventDetailsPage.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/EventDetailsPage/EventDetailsPage.vue



EventDetailsPagevue_type_script_lang_ts.render = render

/* harmony default export */ var EventDetailsPage = (EventDetailsPagevue_type_script_lang_ts);
// CONCATENATED MODULE: ./plugins/EventsEnhanced/vue/src/index.ts
/*!
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

// CONCATENATED MODULE: ./node_modules/@vue/cli-service/lib/commands/build/entry-lib-no-default.js




/***/ })

/******/ });
//# sourceMappingURL=EventsEnhanced.common.js.map