<template>
  <a class="logout" href="#" @click.prevent="logout">
    <slot></slot>
  </a>
</template>

<script lang="ts">
import Vue from 'vue';

export default Vue.extend({
  methods: {
    async logout(): Promise<void> {
      await this.$store.dispatch('auth/tryLogout');
    },
  },
});
</script>
