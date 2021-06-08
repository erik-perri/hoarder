<template>
  <div>
    <router-link
      v-for="info in getPaginationLinks()"
      :to="info.link"
      :key="info.text"
    >
      {{ info.text }}
    </router-link>

    <div v-if="totalPages > 1">Page {{ currentPage }} of {{ totalPages }}</div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';

export default Vue.extend({
  props: {
    totalPages: {
      type: Number,
      required: true,
    },
    currentPage: {
      type: Number,
      required: true,
    },
    routeParameters: {
      type: Object,
      required: true,
    },
  },
  methods: {
    getPaginationLinks() {
      const links = [];

      if (this.currentPage > 1) {
        links.push({
          text: 'Previous',
          link: {
            ...this.routeParameters,
            query: {
              ...(this.routeParameters.query || {}),
              page: this.currentPage - 1,
            },
          },
        });
      }

      if (this.currentPage < this.totalPages) {
        links.push({
          text: 'Next',
          link: {
            ...this.routeParameters,
            query: {
              ...(this.routeParameters.query || {}),
              page: this.currentPage + 1,
            },
          },
        });
      }

      return links;
    },
  },
});
</script>
