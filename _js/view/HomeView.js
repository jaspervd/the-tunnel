'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  '../../_hbs/home.hbs'
], ($, _, Backbone, homeTemplate) => {
  var HomeView = Backbone.View.extend({
    template: homeTemplate,

    initialize: function () {
      _.bindAll.apply(_, [this].concat(_.functions(this)));
    },

    render: function () {
      this.$el.html(this.template());
      return this;
    }
  });

  return HomeView;
});
