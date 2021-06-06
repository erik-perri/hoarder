<template>
  <div>
    <h2>{{ $route.params.id ? 'Edit Collectible' : 'Create Collectible' }}</h2>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';

export default Vue.extend({});
</script>
