/* global define */
'use strict';

import {api} from 'classes/globals';

define([
  'jquery',
  'underscore',
  'backbone',
  '../view/NavigationView',
  '../view/FooterView',
  '../view/HomeView',
  '../view/AddCreationView',
  '../view/ExploreView',
  '../view/detail/CreationDetailView',
  '../view/ArtistsView',
  '../view/detail/ArtistDetailView',
  '../view/ArtistEditView',
  '../view/GroupsView',
  '../view/detail/GroupDetailView',
  '../view/detail/GroupArtistsDetailView',
  '../view/InfoView',
  '../view/LoginView',
  '../view/RegisterView'
], ($, _, Backbone, NavigationView, FooterView, HomeView, AddCreationView, ExploreView, CreationDetailView, ArtistsView, ArtistDetailView, ArtistEditView, GroupsView, GroupDetailView, GroupArtistsDetailView, InfoView, LoginView, RegisterView) => {
  var AppRouter = Backbone.Router.extend({
    initialize: function() {
      _.bindAll.apply(_, [this].concat(_.functions(this)));

      this.navigationView = new NavigationView();
      this.footerView = new FooterView();
      this.authenticationCheck();
    },

    routes: {
      '': 'home',
      'creations/add': 'addcreation',
      'featured': 'home',
      'explore': 'explore',
      'creations/:id': 'creation',
      'artists': 'artists',
      'artists/:id': 'artist',
      'artists/:id/edit': 'artistEdit',
      'groups': 'groups',
      'groups/:id': 'group',
      'groups/:id/artists': 'groupartists',
      'info': 'info',
      'login': 'login',
      'register': 'register',
      'logout': 'logout',
      '*path': 'home'
    },

    home: function() {
      this.render(new HomeView());
    },

    explore: function() {
      this.render(new ExploreView());
    },

    addcreation: function() {
      this.render(new AddCreationView());
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

    artistEdit: function(id) {
      this.render(new ArtistEditView({artist_id: id}));
    },

    groups: function() {
      this.render(new GroupsView());
    },

    group: function(id) {
      this.render(new GroupDetailView({group_id: id}));
    },

    groupartists: function(id) {
      this.render(new GroupArtistsDetailView({group_id: id}));
    },

    info: function() {
      this.render(new InfoView());
    },

    login: function() {
      if(window.user.id > 0) {
        Backbone.history.navigate(`artists/${window.user.id}`, true);
      } else {
        this.render(new LoginView());
      }
    },

    register: function() {
      if(window.user.id > 0) {
        Backbone.history.navigate(`artists/${window.user.id}`, true);
      } else {
        this.render(new RegisterView());
      }
    },

    logout: function() {
      $.post(`${api}/logout`);
      window.user = {};
      Backbone.history.navigate('login', true);
    },

    authenticationCheck: function() {
      window.user = {};
      $.post(`${api}/auth`, (data) => {
        window.user = data;
      }).done(() => {
        this.navigationView.render(); // re-render because window.user is not filled on first render
      });
    },

    render: function(view) {
      var $container = $('.container');
      $container.html('');
      $container.append(this.navigationView.render().$el);
      $container.append(view.render().$el);
      $container.append(this.footerView.render().$el);
    }
  });

  return AppRouter;
});
