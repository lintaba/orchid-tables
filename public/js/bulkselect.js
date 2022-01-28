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
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _bulkselect__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./bulkselect */ "./resources/js/bulkselect.js");
/* harmony import */ var _bulkselect__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_bulkselect__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _tableAdvanced__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./tableAdvanced */ "./resources/js/tableAdvanced.js");
/* harmony import */ var _tableAdvanced__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_tableAdvanced__WEBPACK_IMPORTED_MODULE_1__);



/***/ }),

/***/ "./resources/js/bulkselect.js":
/*!************************************!*\
  !*** ./resources/js/bulkselect.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  var bulkselect = function bulkselect() {
    var $els = $('.cb-checker:not(.cb-checker-loaded)');
    $els.each(function () {
      var id = $(this).data('bulkselect-id');
      var $groupCheckbox = $(this).find('.cb-bulk');
      var $statHolder = $('.cb-counter-' + id);
      $groupCheckbox.addClass('cb-checker-loaded');
      var lastChecked = null;

      var update = function update() {
        var $checkboxes = $('.cb-check-' + id).not('[disabled]');
        var $checked = $checkboxes.filter(':checked');
        $statHolder.text($checked.length + '/' + $checkboxes.length);
      };

      var updateMain = function updateMain() {
        var $checkboxes = $('.cb-check-' + id).not('[disabled]');
        var checkednum = $checkboxes.filter(':checked').length;
        var cbnum = $checkboxes.length;

        if (cbnum === 0 && checkednum === 0) {
          $groupCheckbox.prop('checked', false);
          $groupCheckbox.prop('indeterminate', true);
        } else if (cbnum === checkednum) {
          $groupCheckbox.prop('checked', true);
          $groupCheckbox.prop('indeterminate', false);
        } else if (0 === checkednum) {
          $groupCheckbox.prop('checked', false);
          $groupCheckbox.prop('indeterminate', false);
        } else {
          $groupCheckbox.prop('indeterminate', true);
        }

        update();
      };

      $('.cb-check-' + id).change(updateMain).on('lintaba:checked', updateMain).click(function (e) {
        var $checkboxes = $('.cb-check-' + id).not('[disabled]');
        var clickTarget = this;

        if (!lastChecked) {
          lastChecked = clickTarget;
          return;
        }

        if (e.shiftKey) {
          var start = $checkboxes.index(clickTarget);
          var end = $checkboxes.index(lastChecked);
          $checkboxes.slice(Math.min(start, end), Math.max(start, end) + 1).prop('checked', lastChecked.checked).trigger('lintaba:checked');
        }

        update();
        lastChecked = clickTarget;
      });
      $groupCheckbox.on('click', function () {
        var $checkboxes = $('.cb-check-' + id).not('[disabled]');
        $checkboxes.prop('checked', $groupCheckbox.prop('checked'));
        update();
      });
      updateMain();
    });
  };

  document.addEventListener("turbo:load", bulkselect);
  bulkselect();
});

/***/ }),

/***/ "./resources/js/tableAdvanced.js":
/*!***************************************!*\
  !*** ./resources/js/tableAdvanced.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  var tableAdvanced = function tableAdvanced() {
    var $els = $('tr[data-table-advanced-rowlink]');
    $els.click(function (e) {
      if (!$(e.target).is('a,button,label,input')) {
        var target = $(this).data('table-advanced-rowlink');
        Turbo.visit(target);
      }
    });
  };

  document.addEventListener("turbo:load", tableAdvanced);
  tableAdvanced();
});

/***/ }),

/***/ "./resources/sass/app.scss":
/*!*********************************!*\
  !*** ./resources/sass/app.scss ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!*************************************************************!*\
  !*** multi ./resources/js/app.js ./resources/sass/app.scss ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /Users/lintaba/Work/lintaba/orchid-custom/packages/lintaba/orchid-tables/resources/js/app.js */"./resources/js/app.js");
module.exports = __webpack_require__(/*! /Users/lintaba/Work/lintaba/orchid-custom/packages/lintaba/orchid-tables/resources/sass/app.scss */"./resources/sass/app.scss");


/***/ })

/******/ });