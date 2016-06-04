/* global define */
'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  'model/Group',
  '_hbs/groupdetail.hbs'
], ($, _, Backbone, Group, template) => {
  var GroupDetailView = Backbone.View.extend({
    template: template,
    group_id: null,

    initialize: function (options) {
      this.options = options;
      _.bindAll.apply(_, [this].concat(_.functions(this)));

      this.model = new Group();
      this.model.set('id', this.options.group_id);
      this.model.fetch();
      this.model.on('reset sync', this.render, this);
    },

    render: function () {
      this.$el.html(this.template(this.model.toJSON()));
      return this;
    }
  });

  return GroupDetailView;
});
