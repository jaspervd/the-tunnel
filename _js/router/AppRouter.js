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
  '../view/detail/CreationDetailView',
  '../view/ArtistsView',
  '../view/detail/ArtistDetailView',
  '../view/GroupsView',
  '../view/InfoView',
  '../view/LoginView',
  '../view/RegisterView'
], ($, _, Backbone, NavigationView, FooterView, HomeView, ExploreView, CreationDetailView, ArtistsView, ArtistDetailView, GroupsView, InfoView, LoginView, RegisterView) => {
  var AppRouter = Backbone.Router.extend({
    initialize: function() {
      _.bindAll.apply(_, [this].concat(_.functions(this)));
    },

    routes: {
      '': 'home',
      'featured': 'home',
      'explore': 'explore',
      'creations/:id': 'creation',
      'artists': 'artists',
      'artists/:id': 'artist',
      'groups': 'groups',
      'info': 'info',
      'login': 'login',
      'register': 'register'
    },

    home: function() {
      this.render(new HomeView());
    },

    explore: function() {
      this.render(new ExploreView());
    },

    creation: function(id) {
      this.render(new CreationDetailView({creation_id: id}));
    },

    artists: function() {
      this.render(new ArtistsView());
    },

    artist: function(id) {
      this.render(new ArtistDetailView({artist_id: id}));
    },

    groups: function() {
      this.render(new GroupsView());
    },

    info: function() {
      this.render(new InfoView());
    },

    login: function() {
      this.render(new LoginView());
    },

    register: function() {
      this.render(new RegisterView());
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
