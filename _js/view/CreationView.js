/* global define */
'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  'model/Creation',
  '_hbs/creation.hbs'
], ($, _, Backbone, Creation, template) => {
  var CreationView = Backbone.View.extend({
    template: template,
    tagName: 'li',
    className: 'creation',

    events: {
      '.click .likes': 'likeHandler'
    },

    initialize: function () {
      _.bindAll.apply(_, [this].concat(_.functions(this)));
    },

    likeHandler: function(e) {
      e.preventDefault();
      this.model.like();
    },

    render: function () {
      this.$el.html(this.template(this.model.toJSON()));
      this.delegateEvents();
      return this;
    }
  });

  return CreationView;
});
