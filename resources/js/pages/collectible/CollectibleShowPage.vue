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
          params: { id: this.collectible.id },
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
  components: { Pagination },
  computed: {
    isLoggedIn: function (): boolean {
      return this.$store.getters['auth/isLoggedIn'];
    },
  },
  async created() {
    await this.refreshCollectible();
    await this.refreshList();
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
      const id = parseInt(this.$route.params.id, 10);
      if (!id) {
        throw new Error('Invalid collectible');
      }

      const response = await getCollectible(id);
      if (response.status === 'success') {
        this.collectible = response.data.collectible;
      }
    },
    async refreshList() {
      await this.fetchList(this.getPageFromRoute(1));
    },
    async fetchList(page: number) {
      this.data = null;

      const response = await getCategories(this.collectible.id, page);
      if (response.status === 'success' && response.data) {
        this.data = response.data;
      }
    },
    getPageFromRoute(defaultValue: number): number {
      if (!this.$route.query.page) {
        return defaultValue;
      }

      if (Array.isArray(this.$route.query.page)) {
        return this.$route.query.page[0]
          ? parseInt(this.$route.query.page[0], 10)
          : defaultValue;
      }

      return parseInt(this.$route.query.page, 10);
    },
  },
});
</script>
