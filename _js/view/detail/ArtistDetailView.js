/* global define */
'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  'model/User',
  '_hbs/artistdetail.hbs',
  'collection/Groups',
  'view/GroupView'
], ($, _, Backbone, User, template, Groups, GroupView) => {
  var ArtistDetailView = Backbone.View.extend({
    template: template,
    artist_id: null,

    initialize: function (options) {
      this.options = options;
      _.bindAll.apply(_, [this].concat(_.functions(this)));

      this.model = new User();
      this.model.set('id', this.options.artist_id);
      this.model.fetch();
      this.model.on('reset sync', this.render, this);

      this.getGroups();
    },

    getGroups: function() {
      $.get(`${this.model.urlRoot}${this.model.get('id')}/groups`, (data) => {
        this.renderGroups(new Groups(data));
      });
    },

    renderGroups: function(groups) {
      this.render();
      groups.each((group) => {
        var view = new GroupView({ model: group });
        this.$el.find('.groups').append(view.render().$el);
      });
    },

    render: function () {
      this.$el.html(this.template({artist: this.model.toJSON(), user: window.user}));
      return this;
    }
  });

  return ArtistDetailView;
});
