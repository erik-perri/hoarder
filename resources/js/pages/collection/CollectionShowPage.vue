<template>
  <div>
    <h2>{{ collection.name }}</h2>

    <CollectionGoalList :collection="collection" />
    <CollectionStockList :collection="collection" />
  </div>
</template>

<script lang="ts">
import Vue, { PropType } from 'vue';
import { CollectionGoalList } from '../../components/CollectionGoalList';
import { CollectionStockList } from '../../components/CollectionStockList';
import { Collection } from '../../api/collections';

export default Vue.extend({
  props: {
    collection: {
      type: Object as PropType<Collection>,
      required: true,
    },
  },
  components: { CollectionGoalList, CollectionStockList },
});
</script>
