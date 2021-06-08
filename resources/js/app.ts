import './bootstrap';
import Vue from 'vue';
import router from './router';
import store from './store';
import { getLoginRedirect, storeLoginRedirect } from './util/login';

router.beforeEach((to, from, next) => {
  const isLoggedIn = store.getters['auth/isLoggedIn'];

  const requiresAuth = to.matched.some(
    (record) => record.meta.requiresAuth === true
  );
  if (requiresAuth && !isLoggedIn) {
    storeLoginRedirect(to.path);

    // TODO Flash message explaining why they are not where they wanted to go?
    next({ name: 'login' });
    return;
  }

  const requiresGuest = to.matched.some(
    (record) => record.meta.requiresAuth === false
  );
  if (requiresGuest && isLoggedIn) {
    next(getLoginRedirect());
    return;
  }

  next();
});

new Vue({
  el: '#app',
  created() {
    void this.$store.dispatch('auth/checkAuth');
  },
  router,
  store,
});
