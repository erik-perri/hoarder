import './bootstrap';
import Vue from 'vue';
import router from './router';
import store from './store';
import { CriteriaBuilder } from './components/CriteriaBuilder';
import { FieldEditor } from './components/FieldEditor';

new Vue({
  el: '#app',
  created() {
    // TODO Should we have Laravel pass the auth state in a way we can access without needing
    //      another request (preventing the initially visible logout on a refresh of a logged in
    //      user)?  Another alternative might be to store something like the a logged in boolean
    //      in local storage and use for the initial value while we perform this check.
    void this.$store.dispatch('auth/checkAuth');
  },
  router,
  store,
  components: {
    'criteria-builder': CriteriaBuilder,
    'field-editor': FieldEditor,
  },
});
