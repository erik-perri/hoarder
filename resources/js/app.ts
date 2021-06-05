import './bootstrap';
import Vue from 'vue';
import router from './router';
import { CriteriaBuilder } from './components/CriteriaBuilder';
import { FieldEditor } from './components/FieldEditor';

new Vue({
  el: '#app',
  router,
  components: {
    'criteria-builder': CriteriaBuilder,
    'field-editor': FieldEditor,
  },
});
