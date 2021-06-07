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
import { CollectibleCategory, getCategories } from '../../api/collectibles';
import { Pagination } from '../../components/Pagination';
import { ApiList } from '../../api/types';

export default Vue.extend({
  props: {
    collectible: {
      type: Object,
      required: true,
    },
  },
  computed: {
    isLoggedIn: function (): boolean {
      return this.$store.getters['auth/isLoggedIn'];
    },
  },
  async created() {
    await this.refreshList();
  },
  data() {
    return {
      data: null as ApiList<CollectibleCategory> | null,
    };
  },
  watch: {
    $route: 'refreshList',
  },
  methods: {
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
