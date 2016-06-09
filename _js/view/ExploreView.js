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
    tagName: 'section',
    className: 'explore',

    events: {
      'submit .search': 'filterCollection',
      'click .icon': 'filterCollection',
      'change .filter_month': 'filterCollection',
      'change .checkbox': 'filterCollection'
    },

    initialize: function () {
      this.collection = new Creations();
      this.collection.on('reset sync', this.addAllCreations, this);
      this.collection.fetch({reset: true});
    },

    filterCollection: function(e) {
      e.preventDefault();
      var selectedMonth = parseInt(this.$el.find('.filter_month').val());
      var checkboxes = this.$el.find('input:checkbox[name=type]:checked');
      var arrayFilter = [];
      checkboxes.each(function(){
        arrayFilter.push($(this).val());
      });
      var filteredCreations = this.collection;
      var input = this.$el.find('.searchInput').val();
      if(input !== ''){
        filteredCreations = this.collection.bySearch(input);
      }
      if(!isNaN(selectedMonth)) {
        filteredCreations = filteredCreations.byMonth(selectedMonth);
      }
      if(arrayFilter.length <= 2) {
        filteredCreations = filteredCreations.filterCreations(arrayFilter);
      }
      this.renderFilteredCreations(filteredCreations);
    },

    addCreation: function(creation) {
      var view = new CreationView({ model: creation });
      this.$el.find('.creations').append(view.render().el);
    },

    addAllCreations: function() {
      this.$creations.empty();
      this.collection.each(this.addCreation.bind(this), this);
    },

    renderFilteredCreations: function(creations){
      this.$creations.empty();
      creations.forEach(this.addCreation.bind(this), this);
    },

    render: function () {
      this.$el.html(this.template());
      this.$creations = this.$el.find('.creations');
      return this;
    }
  });

  return ExploreView;
});
