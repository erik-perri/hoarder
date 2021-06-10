<template>
  <div>
    <h2>Search {{ collectible.name }}</h2>

    <form @submit.prevent="refreshList">
      <h4>Category criteria</h4>
      <CriteriaBuilder
        :conditions="categoryCriteria"
        :fields="collectible.category_fields"
        @conditions-changed="setCategoryCriteria"
      />

      <h4>Item criteria</h4>
      <CriteriaBuilder
        :conditions="itemCriteria"
        :fields="collectible.item_fields"
        @conditions-changed="setItemCriteria"
      />

      <button type="submit">Search</button>
    </form>

    <h3>Results</h3>
    <div v-if="isLoading">Loading...</div>
    <div v-else-if="error">An error has occurred: {{ error }}</div>
    <div v-if="data">
      <ul v-if="data.items.length">
        <li v-for="item in data.items" :key="item.id">
          <router-link
            :to="{
              name: 'items.show',
              params: {
                collectible: item.collectible_id,
                category: item.category_id,
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
          name: 'collectibles.search',
          params: { collectible: this.collectible.id },
          hash: this.$route.hash,
        }"
      />
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { ListComponent } from '../../util/ListComponent';
import {
  Collectible,
  CollectibleItem,
  searchItems,
} from '../../api/collectibles';
import { Pagination } from '../../components/Pagination';
import { ApiList, ApiResponse } from '../../api/types';
import {
  CriteriaBuilder,
  CriteriaConditions,
} from '../../components/CriteriaBuilder';

interface Data {
  data: ApiList<CollectibleItem> | null;
  categoryCriteria: CriteriaConditions;
  itemCriteria: CriteriaConditions;
}

interface Methods {
  updateLocation: () => void;
  fetchList: (page: number) => Promise<ApiResponse<ApiList<CollectibleItem>>>;
  setCategoryCriteria: (criteria: CriteriaConditions) => void;
  setItemCriteria: (criteria: CriteriaConditions) => void;
}

interface Props {
  collectible: Collectible;
}

export default Vue.extend<Data, Methods, {}, Props>({
  extends: ListComponent,
  props: {
    collectible: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      data: null,
      categoryCriteria: [],
      itemCriteria: [],
    };
  },
  methods: {
    updateLocation(): void {
      this.$router.push({
        query: {
          criteria: JSON.stringify({
            category: this.categoryCriteria,
            item: this.itemCriteria,
          }),
        },
      });
    },
    async fetchList(page: number) {
      const criteria = this.$route.query.criteria as string;
      if (criteria?.length) {
        const previous = JSON.parse(criteria);
        if (previous?.category) {
          this.categoryCriteria = previous.category;
        }
        if (previous?.item) {
          this.itemCriteria = previous.item;
        }
      }

      return await searchItems(
        this.collectible.id,
        this.categoryCriteria,
        this.itemCriteria,
        page
      );
    },
    setCategoryCriteria(criteria: CriteriaConditions): void {
      this.categoryCriteria = criteria;

      this.updateLocation();
    },
    setItemCriteria(criteria: CriteriaConditions): void {
      this.itemCriteria = criteria;

      this.updateLocation();
    },
  },
  components: { CriteriaBuilder, Pagination },
});
</script>
