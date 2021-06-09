<template>
  <div>
    <router-view
      v-if="collectible"
      :collectible="collectible"
      v-on:refresh-collectible="needsRefresh = true"
    />
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { CombinedVueInstance } from 'vue/types/vue';
import { Collectible, getCollectible } from '../api/collectibles';

interface Data {
  collectible: Collectible | null;
  needsRefresh: boolean;
}

interface Methods {
  setCollectible: (collectible: Collectible) => void;
}

interface Computed {}

interface Props {}

// TODO Figure out a better name/location for this
export default Vue.extend<Data, Methods, Computed, Props>({
  data() {
    return {
      collectible: null as Collectible | null,
      // Whether a child has asked us to refresh the next time a page changes
      needsRefresh: false,
    };
  },
  async beforeRouteEnter(to, from, next) {
    const id = parseInt(to.params.collectible, 10);
    const response = !isNaN(id) ? await getCollectible(id) : null;

    if (response?.status !== 'success') {
      // TODO Flash message
      next({ name: 'collectibles.index' });
      return;
    }

    next((vm) =>
      (
        vm as CombinedVueInstance<Vue, Data, Methods, Computed, Props>
      ).setCollectible(response.data.collectible)
    );
  },
  async beforeRouteUpdate(to, from, next) {
    const id = parseInt(to.params.collectible, 10);
    if (this.needsRefresh || id !== this.collectible?.id) {
      this.needsRefresh = false;
      this.collectible = null;
      const response = await getCollectible(id);
      if (response?.status !== 'success') {
        // TODO Flash message
        next({ name: 'collectibles.index' });
        return;
      }

      this.collectible = response.data.collectible;
    }
    next();
  },
  methods: {
    async setCollectible(collectible: Collectible) {
      this.collectible = collectible;
    },
  },
});
</script>
