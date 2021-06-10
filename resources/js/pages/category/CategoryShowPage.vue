<template>
  <div>
    <h2>{{ category.name }}</h2>

    <CollectibleFieldValueTable
      :fields="collectible.category_fields"
      :values="category.field_values"
    >
      <tr>
        <th>Collectible</th>
        <td>
          <router-link
            :to="{
              name: 'collectibles.show',
              params: { collectible: collectible.id },
            }"
          >
            {{ collectible.name }}
          </router-link>
        </td>
      </tr>
    </CollectibleFieldValueTable>

    <h3>Items</h3>
    <div v-if="isLoading">Loading...</div>
    <div v-else-if="error">An error has occurred: {{ error }}</div>
    <div v-else-if="data">
      <ul v-if="data.items.length">
        <li v-for="item in data.items" :key="item.id">
          <router-link
            :to="{
              name: 'items.show',
              params: {
                collectible: collectible.id,
                category: category.id,
                item: item.id,
              },
            }"
          >
            {{ item.name }}
          </router-link>
        </li>
      </ul>
      <div v-else>No items found.</div>

      <Pagination
        :current-page="getPageFromRoute(1)"
        :total-pages="data.meta.pages"
        :route-parameters="{
          name: 'categories.show',
          params: { collectible: collectible.id, category: category.id },
        }"
      />
    </div>
    <div v-else>Nothing found.</div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { ListComponent } from '../../util/ListComponent';
import {
  Collectible,
  CollectibleCategory,
  CollectibleItem,
  getItems,
} from '../../api/collectibles';
import { Pagination } from '../../components/Pagination';
import { CollectibleFieldValueTable } from '../../components/CollectibleFieldValueTable';
import { ApiList, ApiResponse } from '../../api/types';

interface Data {
  data: ApiList<CollectibleItem> | null;
}

interface Methods {
  fetchList(page: number): Promise<ApiResponse<ApiList<CollectibleItem>>>;
}

interface Computed {
  isLoggedIn: boolean;
}

interface Props {
  collectible: Collectible;
  category: CollectibleCategory;
}

export default Vue.extend<Data, Methods, Computed, Props>({
  extends: ListComponent,
  props: {
    collectible: {
      type: Object,
      required: true,
    },
    category: {
      type: Object,
      required: true,
    },
  },
  computed: {
    isLoggedIn: function (): boolean {
      return this.$store.getters['auth/isLoggedIn'];
    },
  },
  methods: {
    async fetchList(
      page: number
    ): Promise<ApiResponse<ApiList<CollectibleItem>>> {
      return await getItems(this.collectible.id, this.category.id, page);
    },
  },
  components: { CollectibleFieldValueTable, Pagination },
});
</script>
