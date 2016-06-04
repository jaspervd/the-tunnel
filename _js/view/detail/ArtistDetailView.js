/* global define */
'use strict';

define([
  'jquery',
  'underscore',
  'backbone',
  'model/User',
  '_hbs/artistdetail.hbs'
], ($, _, Backbone, User, template) => {
  var ArtistDetailView = Backbone.View.extend({
    template: template,
    artist_id: null,

    initialize: function (options) {
      this.options = options;
      _.bindAll.apply(_, [this].concat(_.functions(this)));

      this.model = new User();
      this.model.set('id', this.options.artist_id);
      this.model.fetch();
      this.model.on('reset sync', this.render, this);
    },

    render: function () {
      this.$el.html(this.template(this.model.toJSON()));
      return this;
    }
  });

  return ArtistDetailView;
});
