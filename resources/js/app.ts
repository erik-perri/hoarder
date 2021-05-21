import './bootstrap';
import { createApp } from 'vue';
import { FilterBuilder } from './components/FilterBuilder';

const app = createApp({});

app.component('filter-builder', FilterBuilder);

app.mount('#app');
