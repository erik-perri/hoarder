import './bootstrap';
import { createApp } from 'vue';
import { CriteriaBuilder } from './components/CriteriaBuilder';

const app = createApp({});

app.component('criteria-builder', CriteriaBuilder);

app.mount('#app');
