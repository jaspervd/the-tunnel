'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  '../view/NavigationView',
  '../view/FooterView',
  '../view/HomeView',
  '../view/InfoView'
], ($, _, Backbone, NavigationView, FooterView, HomeView, InfoView) => {
  var AppRouter = Backbone.Router.extend({
    initialize: function() {
      _.bindAll.apply(_, [this].concat(_.functions(this)));
    },

    routes: {
      '': 'home',
      'featured': 'home',
      'info': 'info'
    },

    home: function() {
      this.homeView = new HomeView();
      this.render(this.homeView);
    },

    info: function() {
      this.infoView = new InfoView();
      this.render(this.infoView);
    },

    render: function(view) {
      var navigationView = new NavigationView();
      var footerView = new FooterView();

      var $container = $('.container');
      $container.html('');
      $container.append(navigationView.render().$el);
      $container.append(view.render().$el);
      $container.append(footerView.render().$el);
    }
  });

  return AppRouter;
});
