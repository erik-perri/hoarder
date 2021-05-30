import './bootstrap';
import { createApp } from 'vue';
import { CriteriaBuilder } from './components/CriteriaBuilder';
import { FieldEditor } from './components/FieldEditor';

const app = createApp({});

app.component('criteria-builder', CriteriaBuilder);
app.component('field-editor', FieldEditor);

app.mount('#app');
