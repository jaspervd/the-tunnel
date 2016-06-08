/* global define */
'use strict';

import {Collection} from 'backbone';
import {api} from '../classes/globals';
import Creation from '../model/Creation';

define([], () => {
  var Creations = Collection.extend({
    model: Creation,
    url: `${api}/creations`,

    byMonth: function(month){
      var filtered = this.filter((creation) => {
        var monthCreation = new Date(creation.get('created_time'));
        var monthSearch = monthCreation.getMonth()+1;
        return parseInt(monthSearch) === parseInt(month);
      });
      return new Creations(filtered);
    },

    bySearch: function(query) {
      var filtered = this.filter((creation) => {
        return creation.get('title').toLowerCase().indexOf(query) > -1 || creation.get('info').toLowerCase().indexOf(query) > -1;
      });
      return new Creations(filtered);
    },

    filterCreations: function(query) {
      return this.filter((creation) => {
        if(query[1] !== undefined){
          return creation.get('type').toLowerCase().indexOf(query[0]) > -1 || creation.get('type').indexOf(query[1]) > -1;
        }else{
          return creation.get('type').toLowerCase().indexOf(query[0]) > -1;
        }
      });
    }
  });

  return Creations;
});
