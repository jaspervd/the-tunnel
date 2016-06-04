'use strict';

import { Model } from 'backbone';

class User extends Model {
  defaults() {
    return {
      id: null,
      username: '',
      email: '',
      firstname: '',
      lastname: '',
      bio: '',
      created_time: ''
    };
  }
}

export default User;
