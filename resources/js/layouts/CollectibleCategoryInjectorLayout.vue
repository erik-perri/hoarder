<template>
  <div>
    <router-view
      v-if="category"
      :category="category"
      :collectible="collectible"
      v-on:refresh-category="needsRefresh = true"
    />
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { CombinedVueInstance } from 'vue/types/vue';
import {
  Collectible,
  CollectibleCategory,
  getCategory,
} from '../api/collectibles';

interface Data {
  category: CollectibleCategory | null;
  needsRefresh: boolean;
}

interface Methods {
  setCategory: (category: CollectibleCategory) => void;
}

interface Computed {}

interface Props {
  collectible: Collectible;
}

// TODO Figure out a better name/location for this
export default Vue.extend<Data, Methods, Computed, Props>({
  props: {
    collectible: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      category: null as CollectibleCategory | null,
      // Whether a child has asked us to refresh the next time a page changes
      needsRefresh: false,
    };
  },
  async beforeRouteEnter(to, from, next) {
    const collectibleId = parseInt(to.params.collectible, 10);
    const categoryId = parseInt(to.params.category, 10);

    const response =
      !isNaN(collectibleId) && !isNaN(categoryId)
        ? await getCategory(collectibleId, categoryId)
        : null;

    if (response?.status !== 'success') {
      // TODO Flash message
      next({ name: 'collectibles.index' });
      return;
    }

    next((vm) =>
      (
        vm as CombinedVueInstance<Vue, Data, Methods, Computed, Props>
      ).setCategory(response.data.category)
    );
  },
  async beforeRouteUpdate(to, from, next) {
    const id = parseInt(to.params.category, 10);
    if (this.needsRefresh || id !== this.category?.id) {
      this.needsRefresh = false;
      this.category = null;
      const response = await getCategory(this.collectible.id, id);
      if (response?.status !== 'success') {
        // TODO Flash message
        next({
          name: 'collectibles.show',
          params: { collectible: to.params.collectible },
        });
        return;
      }

      this.category = response.data.category;
    }
    next();
  },
  methods: {
    setCategory(category: CollectibleCategory): void {
      this.category = category;
    },
  },
});
</script>
