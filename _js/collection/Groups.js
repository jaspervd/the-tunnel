'use strict';

import { Collection } from 'backbone';
import Store from 'backbone.localstorage';
import Group from '../model/Group';
import Settings from '../classes/Settings';

class Groups extends Collection {
    constructor(models, options) {
      super();
      this.model = Group;
      this.url = `${Settings.API}/groups`;

      this.localStorage = new Store('groups-backbone');
      this.comparator = 'order';
      super(models, options);
    }
}

export default new Groups();
