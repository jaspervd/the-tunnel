/* global define */
'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  '../view/NavigationView',
  '../view/FooterView',
  '../view/HomeView',
  '../view/ExploreView',
  '../view/ArtistsView',
  '../view/GroupsView',
  '../view/InfoView',
  '../view/LoginView'
], ($, _, Backbone, NavigationView, FooterView, HomeView, ExploreView, ArtistsView, GroupsView, InfoView, LoginView) => {
  var AppRouter = Backbone.Router.extend({
    initialize: function() {
      _.bindAll.apply(_, [this].concat(_.functions(this)));
    },

    routes: {
      '': 'home',
      'featured': 'home',
      'explore': 'explore',
      'artists': 'artists',
      'groups': 'groups',
      'info': 'info',
      'login': 'login',
      'register': 'login'
    },

    home: function() {
      this.homeView = new HomeView();
      this.render(this.homeView);
    },

    explore: function() {
      this.exploreView = new ExploreView();
      this.render(this.exploreView);
    },

    artists: function() {
      this.artistsView = new ArtistsView();
      this.render(this.artistsView);
    },

    groups: function() {
      this.groupsView = new GroupsView();
      this.render(this.groupsView);
    },

    info: function() {
      this.infoView = new InfoView();
      this.render(this.infoView);
    },

    login: function() {
      this.loginView = new LoginView();
      this.render(this.loginView);
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
