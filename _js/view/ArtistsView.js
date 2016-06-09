/* global define */
'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  '_hbs/artists.hbs',
  'collection/Users',
  'view/ArtistView'
], ($, _, Backbone, template, Users, ArtistView) => {
  var ArtistsView = Backbone.View.extend({
    template: template,
    tagName: 'section',
    className: 'artists',

    events: {
      'submit .search': 'submitSearch',
      'click .icon': 'submitSearch'
    },

    submitSearch: function(e){
      e.preventDefault();
      var $search = this.$el.find('.search');
      var input = $search.val();
      if(input !== ''){
        this.renderFilteredArtists(this.collection.filterUsers(input)
        );
      }else{
        this.collection.fetch();
      }
    },

    initialize: function () {
      //_.bindAll.apply(_, [this].concat(_.functions(this)));

      this.collection = new Users();
      this.collection.on('reset sync', this.addAllArtists, this);
      this.collection.fetch({reset: true});
    },

    addArtist: function(artist) {
      var view = new ArtistView({ model: artist });
      this.$el.find('.artists').append(view.render().$el);
    },

    addAllArtists: function() {
      this.render();
      this.collection.each(this.addArtist.bind(this), this);
    },

    renderFilteredArtists: function(artists){
      this.$artists.empty();
      artists.forEach(this.addArtist, this);
    },

    render: function () {
      this.$el.html(this.template());
      this.$artists = this.$el.find('.artists');
      return this;
    }
  });

  return ArtistsView;
});
