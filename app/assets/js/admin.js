// Globalize jquery
import jQuery from 'jquery';
// Vendors
import "admin-lte"
import "bootstrap"

import "jquery-form"
import "jquery-ui"
import "jquery.scrollto"
import "jquery-slimscroll"

import "x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min"

import "icheck"
import "waypoints/lib/jquery.waypoints"
import "waypoints/lib/shortcuts/sticky.min"
import "select2"
// Configure momentJS locale
import moment from "moment"
import "readmore-js"
import "masonry-layout"

import "eonasdan-bootstrap-datetimepicker"
import "bootstrap-toggle";

import URI from "urijs";
// Custom
import "./admin/Admin"
import "./admin/sidebar"
import "./admin/jquery.confirmExit"
import "./admin/treeview"

// Styles
import "../scss/admin.scss"

global.$ = jQuery;
global.jQuery = jQuery;

// Loading langugage files for select2
//let fallbackLocale = window.navigator.language;
// let languageSelect2 = document.documentElement.getAttribute('select2Locale') || fallbackLocale
// let languageMoment = document.documentElement.getAttribute('momentLocale') || fallbackLocale

let languageSelect2 = 'es'
let languageMoment = 'es'


import(`select2/select2_locale_${languageSelect2}.js`).then(() => {
  // set <html lang="{{language}}">
  document.documentElement.setAttribute('lang', languageSelect2)
}).catch('failed to import select2 locale')

moment.locale(languageMoment)

global.moment = moment;

// Load momentJS locale component
import("moment/locale/" + languageMoment + '.js')
  .catch('failed to load language component for momentJS')

$(function () {
  $('[data-toggle="popover"]').popover();
  console.log('document ready!');
});
