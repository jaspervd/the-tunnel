'use strict';

import { Model } from 'backbone';

class Group extends Model {
  defaults() {
    return {
      id: null,
      title: '',
      info: '',
      creator_id: null,
      approved: false,
      created_time: ''
    };
  }
}

export default Group;
