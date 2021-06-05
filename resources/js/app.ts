import './bootstrap';
import Vue from 'vue';
import { CriteriaBuilder } from './components/CriteriaBuilder';
import { FieldEditor } from './components/FieldEditor';

new Vue({
  el: '#app',
  components: {
    'criteria-builder': CriteriaBuilder,
    'field-editor': FieldEditor,
  },
});
