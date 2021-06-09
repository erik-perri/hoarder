<template>
  <div>
    <h2>Collections</h2>

    <CollectionList />
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { CollectionList } from '../../components/CollectionList';

interface Data {}

interface Methods {}

interface Computed {}

interface Props {}

export default Vue.extend<Data, Methods, Computed, Props>({
  components: { CollectionList },
});
</script>
