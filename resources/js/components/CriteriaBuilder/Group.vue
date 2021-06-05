<template>
  <div class="criteria-group">
    <div v-for="(item, index) in items" :key="item.id">
      <div v-if="index > 0" class="group-type">{{ groupType }}</div>

      <Group
        v-if="item.group_type"
        :id="item.id"
        :group-type="item.group_type"
        :conditions="JSON.parse(JSON.stringify(item.group_conditions))"
        :fields="fields"
        @group-changed="groupChanged"
        @delete-group="deleteItem(index)"
      />

      <Condition
        v-else
        :id="item.id"
        :available-fields="fields"
        :start-editing="item.match_field === ''"
        :field="item.match_field"
        :comparison="item.match_comparison"
        :value="item.match_value"
        @condition-changed="conditionChanged"
        @delete-item="deleteItem(index)"
      />
    </div>

    <div class="add-condition">
      <span v-if="groupType !== 'or'">
        &nbsp;<button @click.prevent="addGroup('or')">Add "or" group</button>
      </span>
      <span v-if="groupType !== 'and'">
        &nbsp;<button @click.prevent="addGroup('and')">Add "and" group</button>
      </span>
      &nbsp;<button @click.prevent="addCondition">Add condition</button>
      &nbsp;<a href="#" @click.prevent="deleteGroup" v-if="canDelete">
        Delete group
      </a>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { v4 as uuid } from 'uuid';
import {
  CriteriaCondition,
  CriteriaGroup,
  CriteriaConditions,
  isCriteriaCondition,
  ConditionOptions,
  isCriteriaGroup,
} from './types';
import Condition from './Condition.vue';

let highestGroupId = 0;

export default Vue.extend({
  components: { Condition },
  props: {
    id: String,
    conditions: Array,
    fields: Array,
    groupType: String,
    canDelete: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      instanceId: highestGroupId++,
      items: this.conditions as CriteriaConditions,
    };
  },
  methods: {
    deleteGroup() {
      this.$emit('delete-group', this.id);
    },
    addGroup(type: 'or' | 'and') {
      this.items.push({
        id: uuid(),
        group_type: type,
        group_conditions: [],
      } as CriteriaGroup);
    },
    addCondition() {
      this.items.push({
        id: uuid(),
        match_field: '',
      } as CriteriaCondition);
    },
    groupChanged(modifiedId: string, changes: CriteriaGroup) {
      const index = this.items.findIndex((item) => item.id === modifiedId);
      if (!isCriteriaGroup(this.items[index])) {
        throw new Error('groupChanged called on non-group item');
      }

      this.items[index] = {
        ...this.items[index],
        group_type: changes.group_type,
        group_conditions: changes.group_conditions,
      } as CriteriaGroup;

      this.emitChanges();
    },
    conditionChanged(modifiedId: string, changes: ConditionOptions) {
      const index = this.items.findIndex((item) => item.id === modifiedId);
      if (!isCriteriaCondition(this.items[index])) {
        throw new Error('conditionChanged called on non-condition item');
      }

      this.items[index] = {
        ...this.items[index],
        match_field: changes.field,
        match_comparison: changes.comparison,
        match_value: changes.value,
      } as CriteriaCondition;

      this.emitChanges();
    },
    emitChanges() {
      this.$emit('group-changed', this.id, {
        group_type: this.groupType,
        group_conditions: JSON.parse(JSON.stringify(this.items)),
      });
    },
    deleteItem(index: number) {
      this.items.splice(index, 1);
      this.emitChanges();
    },
  },
  // Since we can't define Group in components, due to TS causing a type error
  // when we attempt to assign it to 'this', we name it so Phpstorm knows what
  // we're referencing in the template above (Vue seems to know either way).
  name: 'Group',
});
</script>

<style lang="scss">
.criteria-group {
  border-left: 1px solid #ccc;
  padding: 15px;

  .group-type {
    margin: 15px 0;
  }

  .add-condition {
    margin-top: 20px;
  }
}
</style>
