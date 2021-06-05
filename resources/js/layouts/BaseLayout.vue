<template>
  <div>
    <header>
      Hoarder

      <router-link to="/">Home</router-link>
      <router-link to="/collectibles">Collectibles</router-link>
      <router-link to="/login">Login</router-link>
    </header>

    <router-view></router-view>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';

export default Vue.extend({});
</script>
