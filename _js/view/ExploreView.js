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

    events: {
      'click .submitSearch': 'submitSearch',
      'change .filter_month': 'filterMonth',
      'change .checkbox': 'changeCheckbox'
    },

    filterMonth: function(e){
      e.preventDefault();
      var selectedMonth = e.currentTarget.value;
      if(selectedMonth !== ''){
        this.renderFilteredCreations(this.collection.byMonth(selectedMonth));
      }else{
        this.collection.fetch();
      }
    },

    submitSearch: function(e){
      e.preventDefault();
      var $search = this.$el.find('.search');
      var input = $search.val();
      if(input !== ''){
        this.renderFilteredCreations(this.collection.bySearch(input)
        );
      }else{
        this.collection.fetch();
      }
    },

    changeCheckbox: function(e){
      e.preventDefault();
      var checkboxes = $('input:checkbox[name=type]:checked');
      var arrayFilter = [];
      var values = checkboxes.each(function(){
        arrayFilter.push($(this).val());
      });
      if(arrayFilter.length <= 2){
        this.renderFilteredCreations(this.collection.filterCreations(arrayFilter));
      }else{
        this.collection.fetch();
      }
    },

    initialize: function () {
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
