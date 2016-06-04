'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  '../model/Creation',
  '../../_hbs/creation.hbs'
], ($, _, Backbone, Creation, creationTemplate) => {
  var CreationView = Backbone.View.extend({
    template: creationTemplate,
    tagName: 'article',
    className: 'creation',

    initialize: function () {
      _.bindAll.apply(_, [this].concat(_.functions(this)));
    },

    render: function () {
      console.log(this.model);
      this.$el.html(this.template(this.model.toJSON()));
      return this;
    }
  });

  return CreationView;
});
