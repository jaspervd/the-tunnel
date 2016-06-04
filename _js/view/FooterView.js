/* global define */
'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  '_hbs/footer.hbs'
], ($, _, Backbone, template) => {
  var FooterView = Backbone.View.extend({
    tagName: 'footer',
    template: template,

    initialize: function () {
      _.bindAll.apply(_, [this].concat(_.functions(this)));
    },

    render: function () {
      this.$el.html(this.template());
      return this;
    }
  });

  return FooterView;
});
