/* global define */
'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  '_hbs/navigation.hbs'
], ($, _, Backbone, template) => {
  var NavigationView = Backbone.View.extend({
    template: template,

    initialize: function () {
      _.bindAll.apply(_, [this].concat(_.functions(this)));
    },

    render: function () {
      this.$el.html(this.template());
      return this;
    }
  });

  return NavigationView;
});
