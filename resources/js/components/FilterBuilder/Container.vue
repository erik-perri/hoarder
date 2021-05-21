<template>
  <div class="filter-builder">
    <Group
      :conditions="this.items"
      :fields="this.fields"
      :can-delete="false"
      group-type="and"
      @group-changed="groupChanged"
    />
    <input type="hidden" :value="encodeForUrl(filter)" :name="inputName" />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import Group from './Group.vue';
import { FilterConditions } from './types';

export default defineComponent({
  components: { Group },
  props: {
    inputName: {
      type: String,
      required: true,
    },
    conditions: {
      type: Array,
      required: true,
    },
    fields: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      items: JSON.parse(JSON.stringify(this.conditions)) as FilterConditions,
      filter: this.conditions,
    };
  },
  methods: {
    encodeForUrl(filter: FilterConditions) {
      return JSON.stringify(filter);
    },
    groupChanged(id: number, changed: Array<Record<string, string>>) {
      this.filter = changed;
    },
  },
});
</script>

<style lang="scss">
.filter-builder {
  margin-bottom: 15px;
}
</style>
