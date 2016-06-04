/* global define */
'use strict';

import { api } from 'classes/globals';
import { Model } from 'backbone';

define([], () => {
  var Creation = Model.extend({
    id: null,
    user_id: null,
    title: '',
    info: '',
    group_id: null,
    created_time: '',
    likes: null,
    user: {},
    urlRoot: `${api}/creations/`
  });

  return Creation;
});
