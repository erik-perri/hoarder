<template>
  <div>
    <h2>Collections</h2>

    <CollectionList />
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { CollectionList } from '../../components/CollectionList';

export default Vue.extend({
  components: { CollectionList },
});
</script>
