/* global define */
'use strict';

import {api} from 'classes/globals';

define([
  'jquery',
  'underscore',
  'backbone',
  'model/User',
  '_hbs/artistedit.hbs'
], ($, _, Backbone, User, template) => {
  var ArtistEditView = Backbone.View.extend({
    template: template,
    artist_id: null,

    events: {
      'submit form': 'submitHandler'
    },

    initialize: function (options) {
      this.options = options;
      this.artist_id = this.options.artist_id;
      _.bindAll.apply(_, [this].concat(_.functions(this)));

      if(window.user.id !== this.artist_id) {
        Backbone.history.navigate(`artists/${this.artist_id}`, true);
      }

      this.model = new User();
      this.model.set('id', this.artist_id);
      this.model.fetch();
      this.model.on('reset sync', this.render, this);
    },

    submitHandler: function(e) {
      e.preventDefault();
      $.ajax({
        url: `${api}/users/${this.artist_id}`,
        type: 'PUT',
        data: $(e.currentTarget).serialize()
      }).success((data) => {
        console.log(data);
        Backbone.history.navigate(`artists/${data.id}`, true);
      }).fail(() => {
        console.log('error');
      });
    },

    render: function () {
      this.$el.html(this.template(this.model.toJSON()));
      return this;
    }
  });

  return ArtistEditView;
});
