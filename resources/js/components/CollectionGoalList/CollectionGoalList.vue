<template>
  <div>
    <h3>Goals</h3>
    <div v-if="isLoading">Loading goals...</div>
    <div v-else-if="error">An error has occurred: {{ error }}</div>
    <div v-if="data">
      <ul v-if="data.items.length">
        <li v-for="info in data.items" :key="info.goal.id">
          {{ info.goal.name }}:

          <span>{{ info.progress.percent }}%</span>
          ({{ info.progress.stocked }} / {{ info.progress.total }})

          <router-link
            :to="{
              name: 'collectibles.search',
              params: {
                collectible: collection.collectible_id,
              },
              query: {
                criteria: JSON.stringify({
                  category: info.goal.category_criteria,
                  item: info.goal.item_criteria,
                }),
              },
            }"
          >
            Search
          </router-link>
        </li>
      </ul>
      <div v-else>No goals found.</div>
    </div>
  </div>
</template>

<script lang="ts">
import Vue, { PropType } from 'vue';
import { ListComponent } from '../../util/ListComponent';
import { Collection, getGoals, GetGoalsResponse } from '../../api/collections';
import { ApiList, ApiResponse } from '../../api/types';

export default Vue.extend({
  extends: ListComponent,
  props: {
    collection: {
      type: Object as PropType<Collection>,
      required: true,
    },
  },
  data() {
    return {
      data: null as ApiList<GetGoalsResponse> | null,
    };
  },
  methods: {
    async fetchList(
      page: number
    ): Promise<ApiResponse<ApiList<GetGoalsResponse>>> {
      return await getGoals(this.collection.id, page);
    },
  },
});
</script>
