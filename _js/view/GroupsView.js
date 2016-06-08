/* global define */
'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  '_hbs/groups.hbs',
  'collection/Groups',
  'view/GroupView'
], ($, _, Backbone, template, Groups, GroupView) => {
  var GroupsView = Backbone.View.extend({
    template: template,

    events: {
      'click .submitSearch': 'submitSearch'
    },

    submitSearch: function(e){
      e.preventDefault();
      var $search = this.$el.find('.search');
      var input = $search.val();
      if(input !== ''){
        this.renderFilteredGroups(this.collection.filterGroups(input)
        );
      }else{
        this.collection.fetch();
      }
    },

    initialize: function () {
      //_.bindAll.apply(_, [this].concat(_.functions(this)));

      this.collection = new Groups();
      this.collection.on('reset sync', this.addAllGroups, this);
      this.collection.fetch({reset: true});
    },

    addGroup: function(group) {
      var view = new GroupView({ model: group });
      this.$el.find('.groups').append(view.render().$el);
    },

    addAllGroups: function() {
      this.render();
      this.collection.each(this.addGroup.bind(this), this);
    },

    renderFilteredGroups: function(groups){
      this.$groups.empty();
      groups.forEach(this.addGroup, this);
    },

    render: function () {
      this.$el.html(this.template());
      this.$groups = this.$el.find('.groups');
      return this;
    }
  });

  return GroupsView;
});
