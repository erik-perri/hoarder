<template>
  <div>
    <h2>{{ collection.name }}</h2>

    <CollectionGoalList :collection="collection" />
    <CollectionStockList :collection="collection" />
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { CollectionGoalList } from '../../components/CollectionGoalList';
import { CollectionStockList } from '../../components/CollectionStockList';
import { Collection } from '../../api/collections';

interface Data {}

interface Methods {}

interface Computed {}

interface Props {
  collection: Collection;
}

export default Vue.extend<Data, Methods, Computed, Props>({
  props: {
    collection: {
      type: Object,
      required: true,
    },
  },
  components: { CollectionGoalList, CollectionStockList },
});
</script>
