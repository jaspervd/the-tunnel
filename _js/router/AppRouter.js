'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  '../view/HomeView'
], ($, _, Backbone, HomeView) => {
  var AppRouter = Backbone.Router.extend({
    initialize: function() {
      _.bindAll.apply(_, [this].concat(_.functions(this)));
    },

    routes: {
      '': 'home'
    },

    home: function() {
      this.homeView = new HomeView();
      this.render(this.homeView);
    },

    render: function(view) {
      $('.container').html(view.render().$el);
    }
  });

  return AppRouter;
});
