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
            :to="{
              name: 'collectibles.show',
              params: { collectible: item.id },
            }"
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
import { ListComponent } from '../../util/ListComponent';
import { Collectible, getCollectibles } from '../../api/collectibles';
import { Pagination } from '../../components/Pagination';
import { ApiList, ApiResponse } from '../../api/types';

interface Data {
  data: ApiList<Collectible> | null;
}

interface Methods {
  fetchList: (page: number) => Promise<ApiResponse<ApiList<Collectible>>>;
}

interface Computed {
  isLoggedIn: boolean;
}

interface Props {}

export default Vue.extend<Data, Methods, Computed, Props>({
  extends: ListComponent,
  computed: {
    isLoggedIn: function (): boolean {
      return this.$store.getters['auth/isLoggedIn'];
    },
  },
  methods: {
    async fetchList(page: number): Promise<ApiResponse<ApiList<Collectible>>> {
      return await getCollectibles(page);
    },
  },
  components: { Pagination },
});
</script>
