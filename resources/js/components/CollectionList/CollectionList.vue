<template>
  <div>
    <div v-if="isLoading">Loading...</div>
    <div v-else-if="error">An error has occurred: {{ error }}</div>
    <div v-else-if="data">
      <ul>
        <li v-for="item in data.items" :key="item.id">
          <router-link
            :to="{
              name: 'collections.show',
              params: { collection: item.id },
            }"
          >
            {{ item.name }}
          </router-link>
        </li>
      </ul>

      <Pagination
        :current-page="getPageFromRoute(1)"
        :total-pages="data.meta.pages"
        :route-parameters="{ name: 'collections.index' }"
      />
    </div>
    <div v-else>Nothing found.</div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { ListComponent } from '../../util/ListComponent';
import { Collection, getCollections } from '../../api/collections';
import { ApiList, ApiResponse } from '../../api/types';
import { Pagination } from '../Pagination';

export default Vue.extend({
  extends: ListComponent,
  data() {
    return {
      data: null as ApiList<Collection> | null,
    };
  },
  methods: {
    async fetchList(page: number): Promise<ApiResponse<ApiList<Collection>>> {
      return await getCollections(page);
    },
  },
  components: { Pagination },
});
</script>
