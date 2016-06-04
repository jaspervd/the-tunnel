'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  '_hbs/footer.hbs'
], ($, _, Backbone, footerTemplate) => {
  var FooterView = Backbone.View.extend({
    tagName: 'footer',
    template: footerTemplate,

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
