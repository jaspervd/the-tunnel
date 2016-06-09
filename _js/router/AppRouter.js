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
  '../view/AddGroupView',
  '../view/detail/GroupArtistsDetailView',
  '../view/InfoView',
  '../view/LoginView',
  '../view/RegisterView'
], ($, _, Backbone, NavigationView, FooterView, HomeView, AddCreationView, ExploreView, CreationDetailView, ArtistsView, ArtistDetailView, ArtistEditView, GroupsView, GroupDetailView, AddGroupView, GroupArtistsDetailView, InfoView, LoginView, RegisterView) => {
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
      'groups/add': 'addgroup',
      'groups/:id': 'group',
      'groups/:id/artists': 'groupartists',
      'info': 'info',
      'login': 'login',
      'register': 'register',
      'logout': 'logout',
      'error': 'error',
      '*path': 'error'
    },

    home: function() {
      this.navigationView.setCurrentPage('featured');
      this.render(new HomeView());
    },

    explore: function() {
      this.navigationView.setCurrentPage('explore');
      this.render(new ExploreView());
    },

    addcreation: function() {
      this.navigationView.setCurrentPage('creations');
      this.render(new AddCreationView());
    },

    creation: function(id) {
      this.navigationView.setCurrentPage('creations');
      this.render(new CreationDetailView({creation_id: id}));
    },

    artists: function() {
      this.navigationView.setCurrentPage('artists');
      this.render(new ArtistsView());
    },

    artist: function(id) {
      this.navigationView.setCurrentPage('artists');
      this.render(new ArtistDetailView({artist_id: id}));
    },

    artistEdit: function(id) {
      this.navigationView.setCurrentPage('artists');
      this.render(new ArtistEditView({artist_id: id}));
    },

    groups: function() {
      this.navigationView.setCurrentPage('groups');
      this.render(new GroupsView());
    },

    group: function(id) {
      this.navigationView.setCurrentPage('groups');
      this.render(new GroupDetailView({group_id: id}));
    },

    addgroup: function(){
      this.navigationView.setCurrentPage('groups');
      this.render(new AddGroupView());
    },

    groupartists: function(id) {
      this.navigationView.setCurrentPage('groups');
      this.render(new GroupArtistsDetailView({group_id: id}));
    },

    info: function() {
      this.navigationView.setCurrentPage('info');
      this.render(new InfoView());
    },

    login: function() {
      if(window.user.id > 0) {
        Backbone.history.navigate(`artists/${window.user.id}`, true);
      } else {
        this.navigationView.setCurrentPage('login');
        this.render(new LoginView());
      }
    },

    register: function() {
      if(window.user.id > 0) {
        Backbone.history.navigate(`artists/${window.user.id}`, true);
      } else {
        this.navigationView.setCurrentPage('register');
        this.render(new RegisterView());
      }
    },

    logout: function() {
      $.post(`${api}/logout`);
      window.user = {};
      Backbone.history.navigate('login', true);
    },

    error: function(error){
      this.render(new ErrorView());
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
