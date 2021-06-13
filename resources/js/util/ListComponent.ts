import Vue from 'vue';
import { ApiList, ApiResponse } from '../api/types';

export const ListComponent = Vue.extend({
  data() {
    return {
      isLoading: false as boolean,
      error: null as string | null,
      data: null as ApiList<unknown> | null,
    };
  },
  async created() {
    await this.refreshList();
  },
  watch: {
    '$route.query.page': 'refreshList',
  },
  methods: {
    async refreshList(): Promise<void> {
      this.isLoading = true;
      this.error = null;
      this.data = null;

      const response = await this.fetchList(this.getPageFromRoute(1));
      if (response.status === 'success' && response.data) {
        this.data = response.data;
      } else {
        this.error = response.message || 'Unknown error.';
      }

      this.isLoading = false;
    },
    async fetchList(page: number): Promise<ApiResponse<ApiList<unknown>>> {
      throw new Error('fetchList must be overridden');
    },
    getPageFromRoute(defaultPage: number): number {
      if (!this.$route.query.page) {
        return defaultPage;
      }

      const page = parseInt(this.$route.query.page as string, 10);
      return isNaN(page) ? defaultPage : page;
    },
  },
});
