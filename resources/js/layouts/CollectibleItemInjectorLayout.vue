<template>
  <div>
    <router-view
      v-if="item"
      :item="item"
      :category="category"
      :collectible="collectible"
      v-on:refresh-item="needsRefresh = true"
    />
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { CollectibleItem, getItem } from '../api/collectibles';

// TODO Figure out a better name/location for this
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
      item: null as CollectibleItem | null,
      // Whether a child has asked us to refresh the next time a page changes
      needsRefresh: false,
    };
  },
  async beforeRouteEnter(to, from, next) {
    const collectibleId = parseInt(to.params.collectible, 10);
    const categoryId = parseInt(to.params.category, 10);
    const itemId = parseInt(to.params.item, 10);

    const response =
      !isNaN(collectibleId) && !isNaN(categoryId) && !isNaN(itemId)
        ? await getItem(collectibleId, categoryId, itemId)
        : null;

    if (response?.status !== 'success') {
      // TODO Flash message
      next({
        name: 'categories.show',
        params: {
          collectible: to.params.collectible,
          category: to.params.category,
        },
      });
      return;
    }

    // @ts-ignore TODO Switch to `vm as unknown as { setCollectible: (collectible: Collectible) => void }` or figure out
    //                 a better way?
    next((vm) => vm.setItem(response.data.item));
  },
  async beforeRouteUpdate(to, from, next) {
    const id = parseInt(to.params.item, 10);
    if (this.needsRefresh || id !== this.item?.id) {
      this.needsRefresh = false;
      this.item = null;
      const response = await getItem(this.collectible.id, this.category.id, id);
      if (response?.status !== 'success') {
        // TODO Flash message
        next({
          name: 'categories.show',
          params: {
            collectible: this.collectible.id,
            category: this.category.id,
          },
        });
        return;
      }

      this.item = response.data.item;
    }
    next();
  },
  methods: {
    async setItem(item: CollectibleItem) {
      this.item = item;
    },
  },
});
</script>
