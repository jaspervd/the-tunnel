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
    tagName: 'nav',

    initialize: function () {
      _.bindAll.apply(_, [this].concat(_.functions(this)));
      this.currentPage = '';
      console.log(this.currentPage);
    },

    setCurrentPage: function(page) {
      if(page !== this.currentPage) {
        this.currentPage = page;
      }
    },

    render: function () {
      this.$el.html(this.template({user: window.user, currentPage: this.currentPage}));
      return this;
    }
  });

  return NavigationView;
});
