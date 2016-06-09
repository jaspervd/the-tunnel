/* global define */
'use strict';

import {api} from 'classes/globals';

define([
  'jquery',
  'underscore',
  'backbone',
  '_hbs/addcreation.hbs'
], ($, _, Backbone, template) => {
  var AddCreationView = Backbone.View.extend({
    template: template,

    events: {
      'submit form': 'submitHandler'
    },

    initialize: function () {
      _.bindAll.apply(_, [this].concat(_.functions(this)));
    },

    submitHandler: function(e) {
      e.preventDefault();
      var fd = new FormData(e.currentTarget);
      console.log(fd.entries());
      $.ajax({
        url: `${api}/creations`,
        type: 'POST',
        data: new FormData(e.currentTarget),
        contentType: false,
        processData: false
      }).success((data) => {
        Backbone.history.navigate(`creations/${data.id}`, true);
      }).fail(() => {
        console.log('error');
      });
    },

    render: function () {
      this.$el.html(this.template());
      return this;
    }
  });

  return AddCreationView;
});
