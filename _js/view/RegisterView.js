/* global define */
'use strict';

import {api} from 'classes/globals';

define([
  'jquery',
  'underscore',
  'backbone',
  '_hbs/register.hbs',
  'collection/Users'
], ($, _, Backbone, template, Users) => {
  var RegisterView = Backbone.View.extend({
    template: template,
    tagName: 'section',
    className: 'register',

    events: {
      'submit form': 'submitHandler'
    },

    initialize: function () {
      _.bindAll.apply(_, [this].concat(_.functions(this)));
      this.collection = new Users();
    },

    submitHandler: function(e) {
      e.preventDefault();

      $.post(`${api}/users`, this.$el.find('form').serialize(), (data) => {
        window.user = data;
        Backbone.history.navigate(`artists/${data.id}`, true);
      }).fail((data) => {
        console.log('error', data);
      });
    },

    render: function () {
      this.$el.html(this.template());
      return this;
    }
  });

  return RegisterView;
});
