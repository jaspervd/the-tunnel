/* global define */
'use strict';

import {Collection} from 'backbone';
import {api} from '../classes/globals';
import Creation from '../model/Creation';

define([], () => {
  var Creations = Collection.extend({
    model: Creation,
    url: `${api}/creations`
  });

  return Creations;
});
