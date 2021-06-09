<template>
  <div>
    <router-view
      v-if="collection"
      :collection="collection"
      v-on:refresh-collection="needsRefresh = true"
    />
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { CombinedVueInstance } from 'vue/types/vue';
import { Collection, getCollection } from '../api/collections';

interface Data {
  collection: Collection | null;
  needsRefresh: boolean;
}

interface Methods {
  setCollection: (collection: Collection) => void;
}

interface Computed {}

interface Props {}

// TODO Figure out a better name/location for this
export default Vue.extend<Data, Methods, Computed, Props>({
  data() {
    return {
      collection: null,
      // Whether a child has asked us to refresh the next time a page changes
      needsRefresh: false,
    };
  },
  async beforeRouteEnter(to, from, next) {
    const id = parseInt(to.params.collection, 10);
    const response = !isNaN(id) ? await getCollection(id) : null;

    if (response?.status !== 'success') {
      // TODO Flash message
      next({ name: 'collections.index' });
      return;
    }

    next((vm) =>
      (
        vm as CombinedVueInstance<Vue, Data, Methods, Computed, Props>
      ).setCollection(response.data.collection)
    );
  },
  async beforeRouteUpdate(to, from, next) {
    const id = parseInt(to.params.collection, 10);
    if (this.needsRefresh || id !== this.collection?.id) {
      this.needsRefresh = false;
      this.collection = null;
      const response = await getCollection(id);
      if (response?.status !== 'success') {
        // TODO Flash message
        next({ name: 'collections.index' });
        return;
      }

      this.collection = response.data.collection;
    }
    next();
  },
  methods: {
    setCollection(collection: Collection) {
      this.collection = collection;
    },
  },
});
</script>
