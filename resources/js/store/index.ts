// TODO Should we use vuex-typescript for better type checking when calling actions?

import Vue from 'vue';
import Vuex from 'vuex';
import { State } from './state';
import auth from './modules/auth';

const debug = process.env.NODE_ENV !== 'production';

Vue.use(Vuex);

export default new Vuex.Store<State>({
  modules: {
    auth,
  },
  strict: debug,
});
