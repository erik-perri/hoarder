<template>
  <div class="criteria-builder">
    <Group
      :conditions="group.group_conditions"
      :fields="fields"
      :can-delete="false"
      group-type="and"
      @group-changed="groupChanged"
    />
    <input type="hidden" :value="encodeForUrl(criteria)" :name="inputName" />
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { v4 as uuid } from 'uuid';
import Group from './Group.vue';
import {
  CriteriaConditions,
  CriteriaGroup,
  isCriteriaCondition,
  isCriteriaGroup,
} from './types';

export default Vue.extend({
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
        group_conditions: this.conditions as CriteriaConditions,
      }),
      criteria: this.conditions,
    };
  },
  methods: {
    addIdsToGroup(group: CriteriaGroup): CriteriaGroup {
      const result = {
        id: group.id || uuid(),
        group_type: group.group_type,
        group_conditions: [] as CriteriaConditions,
      } as CriteriaGroup;

      for (const condition of group.group_conditions) {
        if (isCriteriaGroup(condition)) {
          result.group_conditions.push(this.addIdsToGroup(condition));
        } else if (isCriteriaCondition(condition)) {
          result.group_conditions.push({
            id: condition.id || uuid(),
            ...condition,
          });
        }
      }

      return result;
    },
    removeIdsFromGroup(group: CriteriaGroup): CriteriaGroup {
      const result = {
        group_type: group.group_type,
        group_conditions: [] as CriteriaConditions,
      } as CriteriaGroup;

      for (const condition of group.group_conditions) {
        if (isCriteriaGroup(condition)) {
          result.group_conditions.push(this.removeIdsFromGroup(condition));
        } else if (isCriteriaCondition(condition)) {
          result.group_conditions.push({
            ...condition,
            id: undefined,
          });
        }
      }

      return result;
    },
    encodeForUrl(criteria: CriteriaConditions) {
      return JSON.stringify(criteria);
    },
    groupChanged(id: number, changed: CriteriaGroup) {
      const groupWithoutIds = this.removeIdsFromGroup(changed);
      // The group passes up a group structure but we only care about the
      // conditions at this level.
      this.criteria = groupWithoutIds.group_conditions;
    },
  },
});
</script>

<style lang="scss">
.criteria-builder {
  margin-bottom: 15px;
}
</style>
