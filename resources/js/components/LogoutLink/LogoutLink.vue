<template>
  <a href="#" @click.prevent="logout">
    <slot></slot>
  </a>
</template>

<script lang="ts">
import Vue from 'vue';

export default Vue.extend({
  methods: {
    async logout() {
      await this.$store.dispatch('auth/tryLogout');
    },
  },
});
</script>
