'use strict';

import 'es5-shim';
import Backbone from 'backbone';
import AppRouter from './router/AppRouter';
import 'helpers';

new AppRouter();
Backbone.history.start();
