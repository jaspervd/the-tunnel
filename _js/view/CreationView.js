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
    tagName: 'article',
    className: 'creation',

    initialize: function () {
      _.bindAll.apply(_, [this].concat(_.functions(this)));
    },

    render: function () {
      this.$el.html(this.template(this.model.toJSON()));
      this.delegateEvents();
      return this;
    }
  });

  return CreationView;
});
