/* global define */
'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  '_hbs/explore.hbs',
  'collection/Creations',
  'view/CreationView'
], ($, _, Backbone, template, Creations, CreationView) => {
  var ExploreView = Backbone.View.extend({
    template: template,

    initialize: function () {
      this.collection = new Creations();
      this.collection.on('reset sync', this.addAllCreations, this);
      this.collection.fetch({reset: true});
    },

    addCreation: function(creation) {
      var view = new CreationView({ model: creation });
      this.$el.append(view.render().$el);
    },

    addAllCreations: function() {
      this.render();
      this.collection.each(this.addCreation.bind(this), this);
    },

    render: function () {
      this.$el.html(this.template());
      return this;
    }
  });

  return ExploreView;
});
