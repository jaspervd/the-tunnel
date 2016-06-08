/* global define */
'use strict';

import {Collection} from 'backbone';
import {api} from '../classes/globals';
import User from '../model/User';

define([], () => {
  var Users = Collection.extend({
    model: User,
    url: `${api}/users`,

    filterUsers: function(query){
      return this.filter(function(user){
        return user.get('username').toLowerCase().indexOf(query) > -1 || user.get('firstname').toLowerCase().indexOf(query) > -1 || user.get('lastname').toLowerCase().indexOf(query) > -1;
      });
    }
  });

  return Users;
});
