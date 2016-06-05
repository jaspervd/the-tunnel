'use strict';

var Handlebars = require('handlebars-template-loader/runtime');

Handlebars.registerHelper('ifEquals', function(v1, v2, options) {
  if(v1 === v2) {
    return options.fn(this);
  }
  return options.inverse(this);
});
