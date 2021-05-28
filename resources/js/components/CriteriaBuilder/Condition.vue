<template>
  <div class="criteria-condition">
    <div v-if="editing" class="condition-input">
      <label :for="`field-${this.id}`">Field</label>
      <select v-model="options.field" :id="`field-${this.id}`">
        <option value=""></option>
        <option
          v-for="field in availableFields"
          :value="field.code"
          :key="field.code"
        >
          {{ field.name }}
        </option>
      </select>
    </div>
    <div v-else>{{ fieldName }}&nbsp;</div>

    <component
      v-if="!!fieldTypeComponent"
      v-bind:is="fieldTypeComponent"
      :editing="editing"
      :comparison="options.comparison"
      :value="options.value"
      @save="(c) => emitChanges(c)"
    />

    &nbsp;<a href="#" v-if="!editing" @click.prevent="editing = true">Edit</a>
    &nbsp;<a href="#" @click.prevent="deleteItem()">Delete</a>
  </div>
</template>

<script lang="ts">
import { defineComponent, shallowRef } from 'vue';
import { Ref } from '@vue/reactivity';
import { CollectibleField } from '../../types';
import { ConditionOptions } from './types';
import TextCondition from './Condition/TextCondition.vue';
import TagCondition from './Condition/TagCondition.vue';
import NumberCondition from './Condition/NumberCondition.vue';
import DateCondition from './Condition/DateCondition.vue';
import BooleanCondition from './Condition/BooleanCondition.vue';

export default defineComponent({
  components: { BooleanCondition },
  props: {
    id: String,
    startEditing: Boolean,
    availableFields: Array,
    field: String,
    comparison: String,
    value: String,
  },
  emits: ['conditionChanged', 'deleteItem'],
  data() {
    return {
      editing: this.startEditing || false,
      options: {
        field: this.field || '',
        comparison: this.comparison,
        value: this.value,
      } as ConditionOptions,
      fieldMapping: {
        text: shallowRef(TextCondition),
        textarea: shallowRef(TextCondition),
        url: shallowRef(TextCondition),
        date: shallowRef(DateCondition),
        number: shallowRef(NumberCondition),
        tags: shallowRef(TagCondition),
        boolean: shallowRef(BooleanCondition),
      } as { [key: string]: Ref },
    };
  },
  watch: {
    'options.field': function () {
      // Reset the value and comparison when the field changes
      this.options.comparison = '';
      this.options.value = '';
    },
  },
  methods: {
    deleteItem() {
      this.$emit('deleteItem', this.id);
    },
    emitChanges(changes: Partial<ConditionOptions>) {
      this.options = {
        ...this.options,
        ...changes,
      };

      // We don't want to pass the Proxy object so we create a new object
      this.$emit('conditionChanged', this.id, { ...this.options });
      this.editing = false;
    },
    getFieldInfo(): CollectibleField | undefined {
      return (this.availableFields as CollectibleField[]).find(
        (field) => field.code === this.options.field
      );
    },
  },
  computed: {
    fieldTypeComponent(): CollectibleField | undefined {
      const info = this.getFieldInfo();
      if (!info) {
        return undefined;
      }

      if (!this.fieldMapping[info.input_type]) {
        throw new Error(
          `No known condition component for type "${info.input_type}"`
        );
      }

      return this.fieldMapping[info.input_type];
    },
    fieldName(): string | undefined {
      const info = this.getFieldInfo();

      return info?.name;
    },
  },
});
</script>

<style lang="scss">
.criteria-condition {
  display: flex;
  flex-direction: row;
}
</style>
