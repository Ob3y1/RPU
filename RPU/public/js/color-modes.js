/******/ (() => { // webpackBootstrap
/*!*************************************!*\
  !*** ./resources/js/color-modes.js ***!
  \*************************************/
/*!
 * Color mode toggler for Bootstrap's docs (https://getbootstrap.com/)
 * Copyright 2011-2022 The Bootstrap Authors
 * Licensed under the Creative Commons Attribution 3.0 Unported License.
 */

(function () {
  'use strict';

  var storedTheme = localStorage.getItem('theme');
  var getPreferredTheme = function getPreferredTheme() {
    if (storedTheme) {
      return storedTheme;
    }
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  };
  var setTheme = function setTheme(theme) {
    if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      document.documentElement.setAttribute('data-bs-theme', 'dark');
    } else {
      document.documentElement.setAttribute('data-bs-theme', theme);
    }
  };
  setTheme(getPreferredTheme());
  var showActiveTheme = function showActiveTheme(theme) {
    var activeThemeIcon = document.querySelector('.theme-icon-active use');
    var btnToActive = document.querySelector("[data-bs-theme-value=\"".concat(theme, "\"]"));
    var svgOfActiveBtn = btnToActive.querySelector('svg use').getAttribute('href');
    document.querySelectorAll('[data-bs-theme-value]').forEach(function (element) {
      element.classList.remove('active');
    });
    btnToActive.classList.add('active');
    activeThemeIcon.setAttribute('href', svgOfActiveBtn);
  };
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function () {
    if (storedTheme !== 'light' || storedTheme !== 'dark') {
      setTheme(getPreferredTheme());
    }
  });
  window.addEventListener('DOMContentLoaded', function () {
    showActiveTheme(getPreferredTheme());
    document.querySelectorAll('[data-bs-theme-value]').forEach(function (toggle) {
      toggle.addEventListener('click', function () {
        var theme = toggle.getAttribute('data-bs-theme-value');
        localStorage.setItem('theme', theme);
        setTheme(theme);
        showActiveTheme(theme);
      });
    });
  });
})();
/******/ })()
;