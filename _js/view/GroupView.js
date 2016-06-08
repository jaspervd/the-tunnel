/* global define */
'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  'model/Group',
  '_hbs/group.hbs'
], ($, _, Backbone, Group, template) => {
  var GroupView = Backbone.View.extend({
    template: template,
    tagName: 'article',
    className: 'group',

    events: {
      'click .join': 'joinHandler'
    },

    initialize: function () {
      _.bindAll.apply(_, [this].concat(_.functions(this)));
    },

    joinHandler: function(e) {
      e.preventDefault();
      $.post(`${this.model.urlRoot}${this.model.get('id')}/join`, (data) => {
        console.log(data);
      });
    },

    render: function () {
      this.$el.html(this.template(this.model.toJSON()));
      return this;
    }
  });

  return GroupView;
});
