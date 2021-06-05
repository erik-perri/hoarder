import './bootstrap';
import Vue from 'vue';
import router from './router';
import store from './store';
import { CriteriaBuilder } from './components/CriteriaBuilder';
import { FieldEditor } from './components/FieldEditor';

router.beforeEach((to, from, next) => {
  const isLoggedIn = store.getters['auth/isLoggedIn'];

  const requiresAuth = to.matched.some(
    (record) => record.meta.requiresAuth === true
  );
  if (requiresAuth && !isLoggedIn) {
    // TODO Flash message explaining why they are not where they wanted to go?
    next({ name: 'login' });
    return;
  }

  const requiresGuest = to.matched.some(
    (record) => record.meta.requiresAuth === false
  );
  if (requiresGuest && isLoggedIn) {
    next({ name: 'home' });
    return;
  }

  next();
});

new Vue({
  el: '#app',
  created() {
    // TODO Should we have Laravel pass the auth state in a way we can access without needing
    //      another request (preventing the initially visible login link on a refresh of a logged in
    //      user)?  Another alternative might be to store something like the a logged in boolean
    //      in local storage and use for the initial value while we perform this check, only
    //      reverting if needed.
    void this.$store.dispatch('auth/checkAuth');
  },
  router,
  store,
  components: {
    'criteria-builder': CriteriaBuilder,
    'field-editor': FieldEditor,
  },
});
