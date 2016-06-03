'use strict';

import { Collection } from 'backbone';
import Store from 'backbone.localstorage';
import Group from '../model/Group';
import Settings from '../classes/Settings';

class Creations extends Collection {
    constructor(models, options) {
      super(models, options);
      this.model = Creation;
      this.url = `${Settings.API}/creations`;

      this.localStorage = new Store('creations-backbone');
      this.comparator = 'order';
    }
}

export default new Creations();
