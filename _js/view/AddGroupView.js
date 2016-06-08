/* global define */
'use strict';

import {api} from 'classes/globals';

define([
  'jquery',
  'underscore',
  'backbone',
  '_hbs/addgroup.hbs'
], ($, _, Backbone, template) => {
  var AddGroupView = Backbone.View.extend({
    template: template,

    events: {
      'submit form': 'submitHandler'
    },

    initialize: function () {
      _.bindAll.apply(_, [this].concat(_.functions(this)));
    },

    submitHandler: function(e) {
      e.preventDefault();
      console.log(e.currentTarget);
      $.ajax({
        url: `${api}/groups`,
        type: 'POST',
        data: new FormData(e.currentTarget),
        contentType: false,
        processData: false
      }).success((data) => {
        Backbone.history.navigate(`groups/${data.id}`, true);
      }).fail(() => {
        console.log('error');
      });
    },

    render: function () {
      this.$el.html(this.template());
      return this;
    }
  });

  return AddGroupView;
});
