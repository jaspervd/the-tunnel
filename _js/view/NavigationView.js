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

    events: {
      'click .navigation a': 'clickHandler'
    },

    initialize: function () {
      _.bindAll.apply(_, [this].concat(_.functions(this)));
    },

    clickHandler: function(e) {
      $(e.currentTarget).addClass('active');
    },

    render: function () {
      this.$el.html(this.template(window.user));
      return this;
    }
  });

  return NavigationView;
});
