<template>
  <div>
    <header>
      Hoarder

      <router-link :to="{ name: 'home' }">Home</router-link>
      <router-link :to="{ name: 'collectibles.index' }">
        Collectibles
      </router-link>
      <router-link v-if="isLoggedIn" :to="{ name: 'collections.index' }">
        Collections
      </router-link>

      <LogoutLink v-if="isLoggedIn">Logout</LogoutLink>

      <router-link v-if="!isLoggedIn" :to="{ name: 'login' }">
        Login
      </router-link>
      <router-link v-if="!isLoggedIn" :to="{ name: 'register' }">
        Register
      </router-link>
    </header>

    <router-view></router-view>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { LogoutLink } from '../components/LogoutLink';

export default Vue.extend({
  computed: {
    isLoggedIn: function (): boolean {
      return this.$store.getters['auth/isLoggedIn'];
    },
  },
  components: { LogoutLink },
});
</script>
