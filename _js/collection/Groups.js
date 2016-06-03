'use strict';

import { Collection } from 'backbone';
import Store from 'backbone.localstorage';
import Group from '../model/Group';
import Settings from '../classes/Settings';

class Groups extends Collection {
    constructor(models, options) {
      super(models, options);
      this.model = Group;
      this.url = `${Settings.API}/groups`;

      this.localStorage = new Store('groups-backbone');
      this.comparator = 'order';
    }
}

export default new Groups();
