<template>
  <div class="filter-group">
    <div v-for="(item, index) in items" :key="item.id">
      <div v-if="index > 0" class="group-type">{{ groupType }}</div>

      <Group
        v-if="item.group_type"
        :id="item.id"
        :group-type="item.group_type"
        :conditions="JSON.parse(JSON.stringify(item.group_conditions))"
        :fields="this.fields"
        @group-changed="groupChanged"
        @delete-group="deleteItem(index)"
      />

      <Condition
        v-else
        :id="item.id"
        :available-fields="this.fields"
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
import { defineComponent } from 'vue';
import { v4 as uuid } from 'uuid';
import {
  FilterCondition,
  FilterGroup,
  FilterConditions,
  isFilterCondition,
  ConditionOptions,
  isFilterGroup,
} from './types';
import Condition from './Condition.vue';

let highestGroupId = 0;

export default defineComponent({
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
  emits: ['groupChanged', 'deleteGroup'],
  data() {
    return {
      instanceId: highestGroupId++,
      items: this.conditions as FilterConditions,
    };
  },
  methods: {
    deleteGroup() {
      this.$emit('deleteGroup', this.id);
    },
    addGroup(type: 'or' | 'and') {
      this.items.push({
        id: uuid(),
        group_type: type,
        group_conditions: [],
      } as FilterGroup);
    },
    addCondition() {
      this.items.push({
        id: uuid(),
        match_field: '',
      } as FilterCondition);
    },
    groupChanged(modifiedId: string, changes: FilterGroup) {
      const index = this.items.findIndex((item) => item.id === modifiedId);
      if (!isFilterGroup(this.items[index])) {
        throw new Error('groupChanged called on non-group item');
      }

      this.items[index] = {
        ...this.items[index],
        group_type: changes.group_type,
        group_conditions: changes.group_conditions,
      } as FilterGroup;

      this.emitChanges();
    },
    conditionChanged(modifiedId: string, changes: ConditionOptions) {
      const index = this.items.findIndex((item) => item.id === modifiedId);
      if (!isFilterCondition(this.items[index])) {
        throw new Error('conditionChanged called on non-condition item');
      }

      this.items[index] = {
        ...this.items[index],
        match_field: changes.field,
        match_comparison: changes.comparison,
        match_value: changes.value,
      } as FilterCondition;

      this.emitChanges();
    },
    emitChanges() {
      this.$emit('groupChanged', this.id, {
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
.filter-group {
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
