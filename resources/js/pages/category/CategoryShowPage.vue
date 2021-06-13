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
import Vue, { PropType } from 'vue';
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

export default Vue.extend({
  extends: ListComponent,
  props: {
    collectible: {
      type: Object as PropType<Collectible>,
      required: true,
    },
    category: {
      type: Object as PropType<CollectibleCategory>,
      required: true,
    },
  },
  data() {
    return {
      data: null as ApiList<CollectibleItem> | null,
    };
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
