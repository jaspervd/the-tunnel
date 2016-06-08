/* global define */
'use strict';

import { api } from 'classes/globals';
import { Model } from 'backbone';

define(['jquery'], ($) => {
  var Creation = Model.extend({
    id: null,
    user_id: null,
    title: '',
    info: '',
    group_id: null,
    created_time: '',
    likes: null,
    user: {},
    urlRoot: `${api}/creations/`,

    like: function() {
      $.post(`${this.urlRoot}${this.get('id')}/like`, (data) => {
        return data;
      });
    }
  });

  return Creation;
});
