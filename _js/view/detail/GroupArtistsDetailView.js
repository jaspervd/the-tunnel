/* global define */
'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  'collection/Users',
  'model/Group',
  '_hbs/groupartists.hbs',
  'view/ArtistView'
], ($, _, Backbone, Users, Group, template, ArtistView) => {
  var GroupArtistsDetailView = Backbone.View.extend({
    template: template,
    group_id: null,

    initialize: function (options) {
      this.options = options;
      _.bindAll.apply(_, [this].concat(_.functions(this)));

      this.collection = new Users();
      this.collection.fetch({data: $.param({group_id: this.options.group_id})});
      this.collection.on('reset sync', this.addAllArtists, this);

      this.model = new Group();
      this.model.set('id', this.options.group_id);
      this.model.fetch();
      this.model.on('reset sync', this.render, this);
    },

    addArtist: function(artist) {
      var view = new ArtistView({ model: artist });
      this.$el.find('.artists').append(view.render().$el);
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

  return GroupArtistsDetailView;
});
