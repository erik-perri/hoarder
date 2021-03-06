<template>
  <div>
    <h2>{{ collectible.name }}</h2>

    <div>
      <router-link
        :to="{
          name: 'collectibles.search',
          params: { collectible: collectible.id },
        }"
      >
        Search collectible
      </router-link>
      <router-link
        v-if="isLoggedIn"
        :to="{ name: 'collectibles.edit', params: { id: collectible.id } }"
      >
        Edit collectible
      </router-link>
    </div>

    <div v-if="isLoading">Loading...</div>
    <div v-else-if="error">An error has occurred: {{ error }}</div>
    <div v-if="data">
      <ul v-if="data.items.length">
        <li v-for="category in data.items" :key="category.id">
          <router-link
            :to="{
              name: 'categories.show',
              params: { collectible: collectible.id, category: category.id },
            }"
          >
            {{ category.name }}
          </router-link>
        </li>
      </ul>
      <div v-else>No categories found.</div>

      <Pagination
        :current-page="getPageFromRoute(1)"
        :total-pages="data.meta.pages"
        :route-parameters="{
          name: 'collectibles.show',
          params: { collectible: this.collectible.id },
        }"
      />
    </div>
  </div>
</template>

<script lang="ts">
import Vue, { PropType } from 'vue';
import { ListComponent } from '../../util/ListComponent';
import {
  Collectible,
  CollectibleCategory,
  getCategories,
} from '../../api/collectibles';
import { Pagination } from '../../components/Pagination';
import { ApiList, ApiResponse } from '../../api/types';

export default Vue.extend({
  extends: ListComponent,
  props: {
    collectible: {
      type: Object as PropType<Collectible>,
      required: true,
    },
  },
  data() {
    return {
      data: null as ApiList<CollectibleCategory> | null,
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
    ): Promise<ApiResponse<ApiList<CollectibleCategory>>> {
      return await getCategories(this.collectible.id, page);
    },
  },
  components: { Pagination },
});
</script>
