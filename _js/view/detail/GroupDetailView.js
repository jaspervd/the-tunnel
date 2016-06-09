/* global define */
'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  'model/Group',
  '_hbs/groupdetail.hbs',
  'collection/Users',
  'collection/Creations',
  'view/CreationView'
], ($, _, Backbone, Group, template, Users, Creations, CreationView) => {
  var GroupDetailView = Backbone.View.extend({
    template: template,
    group_id: null,
    tagName: 'section',
    className: 'detailview',

    initialize: function (options) {
      this.options = options;
      _.bindAll.apply(_, [this].concat(_.functions(this)));

      this.model = new Group();
      this.model.set('id', this.options.group_id);
      this.model.fetch();
      this.model.on('reset sync', this.render, this);

      this.getArtists();
      this.getCreations();
    },

    getArtists: function() {
      $.get(`${this.model.urlRoot}${this.model.get('id')}/users`, (data) => {
        this.model.set('users', new Users(data).toJSON());
        this.render();
      });
    },

    getCreations: function() {
      $.get(`${this.model.urlRoot}${this.model.get('id')}/creations`, (data) => {
        this.renderCreations(new Creations(data));
      });
    },

    renderCreations: function(creations) {
      this.render();
      creations.each((creation) => {
        var view = new CreationView({ model: creation });
        this.$el.find('.creations').append(view.render().$el);
      });
    },

    render: function () {
      this.$el.html(this.template(this.model.toJSON()));
      return this;
    }
  });

  return GroupDetailView;
});
