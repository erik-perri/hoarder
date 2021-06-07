<template>
  <div>
    <h2>{{ collectible.name }}</h2>

    <div v-if="isLoggedIn">
      <router-link
        :to="{ name: 'collectibles.edit', params: { id: collectible.id } }"
      >
        Edit collectible
      </router-link>
    </div>

    <div v-if="data">
      <ul>
        <li v-for="category in data.items" :key="category.id">
          {{ category.name }}
        </li>
      </ul>

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
import Vue from 'vue';
import {
  Collectible,
  CollectibleCategory,
  getCategories,
  getCollectible,
} from '../../api/collectibles';
import { Pagination } from '../../components/Pagination';
import { ApiList } from '../../api/types';

export default Vue.extend({
  computed: {
    isLoggedIn: function (): boolean {
      return this.$store.getters['auth/isLoggedIn'];
    },
  },
  async created() {
    await Promise.all([this.refreshCollectible(), this.refreshList()]);
  },
  data() {
    return {
      collectible: {} as Collectible,
      data: null as ApiList<CollectibleCategory> | null,
    };
  },
  watch: {
    $route: 'refreshList',
  },
  methods: {
    async refreshCollectible() {
      const response = await getCollectible(this.getCollectibleIdFromRoute());
      if (response.status === 'success') {
        this.collectible = response.data.collectible;
      }
    },
    async refreshList() {
      await this.fetchList(this.getPageFromRoute(1));
    },
    async fetchList(page: number) {
      this.data = null;

      const response = await getCategories(
        this.getCollectibleIdFromRoute(),
        page
      );
      if (response.status === 'success' && response.data) {
        this.data = response.data;
      }
    },
    getCollectibleIdFromRoute(): number {
      if (!this.$route.params.id) {
        throw new Error('No collectible ID provided in route');
      }
      return parseInt(this.$route.params.id, 10);
    },
    getPageFromRoute(defaultPage: number): number {
      if (!this.$route.query.page) {
        return defaultPage;
      }

      const page = parseInt(this.$route.query.page as string, 10);
      return isNaN(page) ? defaultPage : page;
    },
  },
  components: { Pagination },
});
</script>
