/* global define */
'use strict';

import {Collection} from 'backbone';
import {api} from '../classes/globals';
import Group from '../model/Group';

define([], () => {
  var Groups = Collection.extend({
    model: Group,
    url: `${api}/groups`,

    filterGroups: function(query){
      return this.filter((group) => {
        return group.get('title').toLowerCase().indexOf(query) > -1 || group.get('info').toLowerCase().indexOf(query) > -1;
      });
    }
  });

  return Groups;
});
