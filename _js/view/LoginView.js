/* global define */
'use strict';

import {api} from 'classes/globals';

define([
  'jquery',
  'underscore',
  'backbone',
  '_hbs/login.hbs'
], ($, _, Backbone, template) => {
  var LoginView = Backbone.View.extend({
    template: template,

    events: {
      'submit form': 'submitHandler'
    },

    initialize: function () {
      //_.bindAll.apply(_, [this].concat(_.functions(this)));
    },

    submitHandler: function(e) {
      e.preventDefault();
      $.post(`${api}/auth`, this.$el.find('form').serialize(), (data) => {
        window.user = data;
        Backbone.history.navigate('featured', true);
      }).fail(function() {
        console.log('error');
      });
    },

    render: function () {
      this.$el.html(this.template());
      return this;
    }
  });

  return LoginView;
});
