<template>
  <div>
    <h2>Collectibles</h2>

    <div v-if="isLoggedIn">
      <router-link :to="{ name: 'collectibles.create' }">
        Create collectible
      </router-link>
    </div>

    <div v-if="isLoading">Loading...</div>
    <div v-else-if="error">An error has occurred: {{ error }}</div>
    <div v-else-if="data">
      <ul>
        <li v-for="item in data.items" :key="item.id">
          <router-link
            :to="{ name: 'collectibles.show', params: { id: item.id } }"
          >
            {{ item.name }}
          </router-link>
        </li>
      </ul>

      <Pagination
        :current-page="getPageFromRoute(1)"
        :total-pages="data.meta.pages"
        :route-parameters="{ name: 'collectibles.index' }"
      />
    </div>
    <div v-else>Nothing found.</div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { Collectible, getCollectibles } from '../../api/collectibles';
import { ApiList } from '../../api/types';
import { Pagination } from '../../components/Pagination';

export default Vue.extend({
  components: { Pagination },
  computed: {
    isLoggedIn: function (): boolean {
      return this.$store.getters['auth/isLoggedIn'];
    },
  },
  data() {
    return {
      isLoading: false,
      error: null as string | null,
      data: null as ApiList<Collectible> | null,
    };
  },
  created() {
    this.refreshList();
  },
  watch: {
    $route: 'refreshList',
  },
  methods: {
    refreshList() {
      this.fetchList(this.getPageFromRoute(1));
    },
    async fetchList(page: number) {
      this.isLoading = true;
      this.error = null;
      this.data = null;

      const response = await getCollectibles(page);
      if (response.status === 'success' && response.data) {
        this.data = response.data;
      } else {
        this.error = response.message || 'Unknown error.';
      }

      this.isLoading = false;
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
