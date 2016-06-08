/* global define */
'use strict';

import { api } from 'classes/globals';
import { Model } from 'backbone';

define([], () => {

  var User = Model.extend({
    id: null,
    username: '',
    email: '',
    firstname: '',
    lastname: '',
    bio: '',
    created_time: '',
    urlRoot: `${api}/users/`
  });

  return User;
});
