<template>
  <div>
    <h2>{{ category.name }}</h2>

    <pre>{{ category.field_values }}</pre>

    <div v-if="isLoading">Loading...</div>
    <div v-else-if="error">An error has occurred: {{ error }}</div>
    <div v-else-if="data">
      <ul v-if="data.items.length">
        <li v-for="item in data.items" :key="item.id">
          {{ item.name }}
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
import { CollectibleItem, getItems } from '../../api/collectibles';
import { ApiList } from '../../api/types';
import { Pagination } from '../../components/Pagination';

export default Vue.extend({
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
  data() {
    return {
      isLoading: false,
      error: null as string | null,
      data: null as ApiList<CollectibleItem> | null,
    };
  },
  computed: {
    isLoggedIn: function (): boolean {
      return this.$store.getters['auth/isLoggedIn'];
    },
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

      const response = await getItems(
        this.collectible.id,
        this.category.id,
        page
      );
      if (response.status === 'success' && response.data) {
        this.data = response.data;
      } else {
        this.error = response.message || 'Unknown error.';
      }

      this.isLoading = false;
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
