'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  '_hbs/navigation.hbs'
], ($, _, Backbone, navigationTemplate) => {
  var NavigationView = Backbone.View.extend({
    template: navigationTemplate,

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
