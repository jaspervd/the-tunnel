/* global define */
'use strict';

import {Collection} from 'backbone';
import {api} from '../classes/globals';
import User from '../model/User';

define([], () => {
  var Users = Collection.extend({
    model: User,
    url: `${api}/users`
  });

  return Users;
});
