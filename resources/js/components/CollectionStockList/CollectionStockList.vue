<template>
  <div>
    <h3>Stock</h3>
    <div v-if="isLoading">Loading stock...</div>
    <div v-else-if="error">An error has occurred: {{ error }}</div>
    <div v-if="data">
      <table v-if="data.items.length">
        <thead>
          <tr>
            <th>Count</th>
            <th>Item</th>
            <th>Category</th>
            <th>Condition</th>
            <th>Language</th>
            <th>Tags</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="stock in data.items" :key="stock.id">
            <td>{{ stock.count }}</td>
            <td>{{ stock.item.name }}</td>
            <td>{{ stock.item.category_id }}</td>
            <td>{{ stock.condition }}</td>
            <td>{{ stock.language }}</td>
            <td>{{ stock.tags }}</td>
          </tr>
        </tbody>
      </table>
      <div v-else>No stock found.</div>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { ListComponent } from '../../util/ListComponent';
import { Collection, getStock, Stock } from '../../api/collections';
import { ApiList, ApiResponse } from '../../api/types';

interface Data {
  data: ApiList<Stock> | null;
}

interface Methods {
  fetchList: (page: number) => Promise<ApiResponse<ApiList<Stock>>>;
}

interface Computed {}

interface Props {
  collection: Collection;
}

export default Vue.extend<Data, Methods, Computed, Props>({
  extends: ListComponent,
  props: {
    collection: {
      type: Object,
      required: true,
    },
  },
  methods: {
    async fetchList(page: number) {
      return await getStock(this.collection.id, page);
    },
  },
});
</script>
