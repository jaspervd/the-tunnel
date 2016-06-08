/* global define */
'use strict';

import {api} from 'classes/globals';

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
      this.count = 0;
      $.get(`${api}/creations/count`, (data) => {
        this.count = data; this.render();
      });
    },

    render: function () {
      this.$el.html(this.template({count: this.count}));
      return this;
    }
  });

  return FooterView;
});
