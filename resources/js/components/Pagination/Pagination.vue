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
import Vue, { PropType } from 'vue';
import { Location } from 'vue-router/types/router';

interface PaginationLink {
  text: string;
  link: Location;
}

export default Vue.extend({
  props: {
    totalPages: {
      type: Number as PropType<number>,
      required: true,
    },
    currentPage: {
      type: Number as PropType<number>,
      required: true,
    },
    routeParameters: {
      type: Object as PropType<Location>,
      required: true,
    },
  },
  methods: {
    getPaginationLinks(): Array<PaginationLink> {
      const links = [];

      if (this.currentPage > 1) {
        links.push({
          text: 'Previous',
          link: {
            ...this.routeParameters,
            query: {
              ...(this.routeParameters.query || {}),
              page: (this.currentPage - 1).toString(10),
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
              page: (this.currentPage + 1).toString(10),
            },
          },
        });
      }

      return links;
    },
  },
});
</script>
