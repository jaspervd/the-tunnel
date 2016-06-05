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

    render: function () {
      this.$el.html(this.template());
      return this;
    }
  });

  return GroupsView;
});
