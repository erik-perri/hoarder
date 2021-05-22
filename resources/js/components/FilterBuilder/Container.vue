<template>
  <div class="filter-builder">
    <Group
      :conditions="group.group_conditions"
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
import { v4 as uuid } from 'uuid';
import Group from './Group.vue';
import {
  FilterConditions,
  FilterGroup,
  isFilterCondition,
  isFilterGroup,
} from './types';

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
      group: this.addIdsToGroup({
        group_conditions: this.conditions as FilterConditions,
      }),
      filter: this.conditions,
    };
  },
  methods: {
    addIdsToGroup(group: FilterGroup): FilterGroup {
      const result = {
        id: group.id || uuid(),
        group_type: group.group_type,
        group_conditions: [] as FilterConditions,
      } as FilterGroup;

      for (const condition of group.group_conditions) {
        if (isFilterGroup(condition)) {
          result.group_conditions.push(this.addIdsToGroup(condition));
        } else if (isFilterCondition(condition)) {
          result.group_conditions.push({
            id: condition.id || uuid(),
            ...condition,
          });
        }
      }

      return result;
    },
    removeIdsFromGroup(group: FilterGroup): FilterGroup {
      const result = {
        group_type: group.group_type,
        group_conditions: [] as FilterConditions,
      } as FilterGroup;

      for (const condition of group.group_conditions) {
        if (isFilterGroup(condition)) {
          result.group_conditions.push(this.removeIdsFromGroup(condition));
        } else if (isFilterCondition(condition)) {
          result.group_conditions.push({
            ...condition,
            id: undefined,
          });
        }
      }

      return result;
    },
    encodeForUrl(filter: FilterConditions) {
      return JSON.stringify(filter);
    },
    groupChanged(id: number, changed: FilterGroup) {
      const groupWithoutIds = this.removeIdsFromGroup(changed);
      // The group passes up a group structure but we only care about the
      // conditions at this level.
      this.filter = groupWithoutIds.group_conditions;
    },
  },
});
</script>

<style lang="scss">
.filter-builder {
  margin-bottom: 15px;
}
</style>
