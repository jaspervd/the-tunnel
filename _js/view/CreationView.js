'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  '../model/creation',
  '../../_hbs/creation.hbs'
  ], ($, _, Backbone, Creation, creationTemplate) => {
    var CreationView = Backbone.View.extend({
      template = creationTemplate,

      initialize: function () {
        _.bindAll.apply(_, [this].concat(_.functions(this)));
      },

      render: function () {
        this.$el.html(this.template(this.model.toJSON()));
        return this;
      }
    });

    return CreationView;
  });
