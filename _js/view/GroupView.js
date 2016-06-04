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

    initialize: function () {
      _.bindAll.apply(_, [this].concat(_.functions(this)));
    },

    render: function () {
      this.$el.html(this.template(this.model.toJSON()));
      this.delegateEvents();
      return this;
    }
  });

  return GroupView;
});
