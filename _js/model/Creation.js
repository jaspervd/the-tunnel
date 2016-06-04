'use strict';

import { Model } from 'backbone';

class Creation extends Model {
  defaults() {
    return {
      id: null,
      user_id: null,
      title: '',
      info: '',
      group_id: null,
      created_time: '',
      likes: null,
      user: {}
    };
  }
}

export default Creation;
