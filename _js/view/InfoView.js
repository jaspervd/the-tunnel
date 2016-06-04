'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  '_hbs/info.hbs'
], ($, _, Backbone, infoTemplate) => {
  var InfoView = Backbone.View.extend({
    template: infoTemplate,

    initialize: function () {
      //_.bindAll.apply(_, [this].concat(_.functions(this)));
    },

    render: function () {
      this.$el.html(this.template());
      return this;
    }
  });

  return InfoView;
});
