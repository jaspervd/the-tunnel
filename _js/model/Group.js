/* global define */
'use strict';

import { api } from 'classes/globals';
import { Model } from 'backbone';

define([], () => {
  var Group = Model.extend({
    id: null,
    title: '',
    info: '',
    creator_id: null,
    approved: false,
    created_time: '',
    urlRoot: `${api}/groups/`
  });

  return Group;
});
