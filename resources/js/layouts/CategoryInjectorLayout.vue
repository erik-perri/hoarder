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
import { CollectibleCategory, getCategory } from '../api/collectibles';

// TODO Figure out a better name/location for this
export default Vue.extend({
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

    // @ts-ignore TODO Switch to `vm as unknown as { setCollectible: (collectible: Collectible) => void }` or figure out
    //                 a better way?
    next((vm) => vm.setCategory(response.data.category));
  },
  async beforeRouteUpdate(to, from, next) {
    const id = parseInt(to.params.category, 10);
    if (this.needsRefresh || id !== this.category?.id) {
      this.needsRefresh = false;
      this.category = null;
      const response = await getCategory(this.collectible.id, id);
      if (response?.status !== 'success') {
        // TODO Flash message
        next({ name: 'collectibles.index' });
        return;
      }

      this.category = response.data.category;
    }
    next();
  },
  methods: {
    async setCategory(category: CollectibleCategory) {
      this.category = category;
    },
  },
});
</script>
