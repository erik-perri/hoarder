<template>
  <div class="filter-builder">
    <Group
      :conditions="JSON.parse(JSON.stringify(this.conditions))"
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
      filter: this.conditions,
    };
  },
  methods: {
    encodeForUrl(filter: FilterConditions) {
      return JSON.stringify(filter);
    },
    groupChanged(id: number, changed: FilterGroup) {
      // The group passes up a group structure but we only care about the
      // conditions at this level.
      this.filter = changed.group_conditions;
    },
  },
});
</script>

<style lang="scss">
.filter-builder {
  margin-bottom: 15px;
}
</style>
