/* global define */
'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  '_hbs/error.hbs',
  'collection/Creations',
  'view/ErrorView'
], ($, _, Backbone, template, Creations, ErrorView) => {
  var ErrorView = Backbone.View.extend({
    template: template,

    /*initialize: function () {
      this.collection = new Creations();
      this.collection.on('reset sync', this.addAllCreations, this);
      this.collection.fetch({reset: true});
    },

    addCreation: function(creation) {
      var view = new CreationView({ model: creation });
      this.$el.find('.creations').append(view.render().el);
    },

    addAllCreations: function() {
      this.render();
      this.collection.each(this.addCreation.bind(this), this);
    },

    renderFilteredCreations: function(creations){
      this.$creations.empty();
      creations.forEach(this.addCreation, this);
    },

    render: function () {
      this.$el.html(this.template());
      this.$creations = this.$el.find('.creations');
      return this;
    }*/
  });

  return ErrorView;
});
