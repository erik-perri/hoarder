<template>
  <div class="criteria-builder">
    <Group
      :conditions="group.group_conditions"
      :fields="fields"
      :can-delete="false"
      group-type="and"
      @group-changed="groupChanged"
    />
  </div>
</template>

<script lang="ts">
import Vue, { PropType } from 'vue';
import { v4 as uuid } from 'uuid';
import Group from './Group.vue';
import {
  CriteriaConditions,
  CriteriaGroup,
  isCriteriaCondition,
  isCriteriaGroup,
} from './types';
import { CollectibleFieldModel } from '../../api/collectibles';

export default Vue.extend({
  props: {
    conditions: {
      type: Array as PropType<CriteriaConditions>,
      required: true,
    },
    fields: {
      type: Array as PropType<Array<CollectibleFieldModel>>,
      required: true,
    },
  },
  created() {
    this.group = this.addIdsToGroup({
      group_conditions: this.conditions as CriteriaConditions,
    } as CriteriaGroup);
  },
  data() {
    return {
      group: {
        group_type: 'and',
        group_conditions: [] as CriteriaConditions,
      } as CriteriaGroup,
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
    groupChanged(id: number, changed: CriteriaGroup): void {
      const groupWithoutIds = this.removeIdsFromGroup(changed);
      // The group passes up a group structure but we only care about the
      // conditions at this level.
      this.$emit('conditions-changed', groupWithoutIds.group_conditions);
    },
  },
  components: { Group },
});
</script>

<style lang="scss">
.criteria-builder {
  margin-bottom: 15px;
}
</style>
