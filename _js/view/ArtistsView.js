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

    initialize: function () {
      //_.bindAll.apply(_, [this].concat(_.functions(this)));

      this.collection = new Users();
      this.collection.on('reset sync', this.addAllArtists, this);
      this.collection.fetch({reset: true});
    },

    addArtist: function(artist) {
      var view = new ArtistView({ model: artist });
      this.$el.append(view.render().$el);
    },

    addAllArtists: function() {
      this.render();
      this.collection.each(this.addArtist.bind(this), this);
    },

    render: function () {
      this.$el.html(this.template());
      return this;
    }
  });

  return ArtistsView;
});
