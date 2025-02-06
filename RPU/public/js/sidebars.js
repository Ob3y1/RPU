/******/ (() => { // webpackBootstrap
/*!**********************************!*\
  !*** ./resources/js/sidebars.js ***!
  \**********************************/
/* global bootstrap: false */
(function () {
  'use strict';

  var tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.forEach(function (tooltipTriggerEl) {
    new bootstrap.Tooltip(tooltipTriggerEl);
  });
})();
/******/ })()
;