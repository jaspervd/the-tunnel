/* global define */
'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  'model/Creation',
  '_hbs/creationdetail.hbs'
], ($, _, Backbone, Creation, template) => {
  var CreationDetailView = Backbone.View.extend({
    template: template,
    creation_id: null,

    initialize: function (options) {
      this.options = options;
      _.bindAll.apply(_, [this].concat(_.functions(this)));

      this.model = new Creation();
      this.model.set('id', this.options.creation_id);
      this.model.fetch();
      this.model.on('reset sync', this.render, this);
    },

    render: function () {
      this.$el.html(this.template(this.model.toJSON()));
      return this;
    }
  });

  return CreationDetailView;
});
