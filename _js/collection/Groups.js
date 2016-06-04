/* global define */
'use strict';

import {Collection} from 'backbone';
import {api} from '../classes/globals';
import Group from '../model/Group';

define([], () => {
  var Groups = Collection.extend({
    model: Group,
    url: `${api}/groups`
  });

  return Groups;
});
