'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  '_hbs/home.hbs',
  'collection/Creations',
  'view/CreationView'
], ($, _, Backbone, homeTemplate, Creations, CreationView) => {
  var HomeView = Backbone.View.extend({
    template: homeTemplate,

    initialize: function () {
      //_.bindAll.apply(_, [this].concat(_.functions(this)));

      this.collection = new Creations();
      this.collection.on('reset sync', this.addAllCreations, this);
      this.collection.fetch({data: $.param({featured: 1})});
    },

    addCreation: function(creation) {
      var view = new CreationView({ model: creation });
      this.$el.find('.featured').append(view.render().$el);
    },

    addAllCreations: function() {
      this.collection.each(this.addCreation.bind(this), this);
    },

    render: function () {
      this.$el.html(this.template());
      return this;
    }
  });

  return HomeView;
});
