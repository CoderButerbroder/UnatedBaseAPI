$(function() {
  'use strict'

  if ($(".js-example-basic-single").length) {
    $(".js-example-basic-single").select2({
      language: "ru"
    });
  }
  if ($(".js-example-basic-multiple").length) {
    $(".js-example-basic-multiple").select2({
      language: "ru"
    });
  }
  if ($(".select2-single").length) {
    $(".select2-single").select2({
      language: "ru"
    });
  }


});
