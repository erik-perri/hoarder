<template>
  <div>
    <header>
      Hoarder

      <router-link to="/">Home</router-link>
      <router-link to="/collectibles">Collectibles</router-link>
      <router-link v-if="isLoggedIn" to="/collections">Collections</router-link>

      <LogoutLink v-if="isLoggedIn">Logout</LogoutLink>

      <router-link v-if="!isLoggedIn" to="/login">Login</router-link>
      <router-link v-if="!isLoggedIn" to="/register">Register</router-link>
    </header>

    <router-view></router-view>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import LogoutLink from '../components/LogoutLink/LogoutLink.vue';

export default Vue.extend({
  components: { LogoutLink },
  computed: {
    isLoggedIn: function (): boolean {
      return this.$store.getters['auth/isLoggedIn'];
    },
  },
});
</script>
