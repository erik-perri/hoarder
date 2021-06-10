<template>
  <a class="logout" href="#" @click.prevent="logout">
    <slot></slot>
  </a>
</template>

<script lang="ts">
import Vue from 'vue';

interface Data {}

interface Methods {
  logout: () => Promise<void>;
}

interface Computed {}

interface Props {}

export default Vue.extend<Data, Methods, Computed, Props>({
  methods: {
    async logout(): Promise<void> {
      await this.$store.dispatch('auth/tryLogout');
    },
  },
});
</script>
