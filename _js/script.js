'use strict';

import 'es5-shim';
import Backbone from 'backbone';
import AppRouter from './router/AppRouter';

new AppRouter();
Backbone.history.start();
