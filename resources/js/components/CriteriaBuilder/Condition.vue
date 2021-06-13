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
      :is="fieldTypeComponent"
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
import Vue, { PropType } from 'vue';
import { CollectibleFieldModel } from '../../api/collectibles';
import { ConditionOptions } from './types';
import BooleanCondition from './Condition/BooleanCondition.vue';
import DateCondition from './Condition/DateCondition.vue';
import NumberCondition from './Condition/NumberCondition.vue';
import TagCondition from './Condition/TagCondition.vue';
import TextCondition from './Condition/TextCondition.vue';

export default Vue.extend({
  props: {
    id: String as PropType<string>,
    startEditing: Boolean as PropType<boolean>,
    availableFields: Array as PropType<Array<CollectibleFieldModel>>,
    field: String as PropType<string>,
    comparison: String as PropType<string>,
    value: String as PropType<string>,
  },
  data() {
    return {
      editing: (this.startEditing || false) as boolean,
      options: {
        field: this.field || '',
        comparison: this.comparison,
        value: this.value,
      } as ConditionOptions,
      fieldMapping: {
        text: 'TextCondition',
        textarea: 'TextCondition',
        url: 'TextCondition',
        date: 'DateCondition',
        number: 'NumberCondition',
        tags: 'TagCondition',
        boolean: 'BooleanCondition',
      } as Record<string, string>,
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
    deleteItem(): void {
      this.$emit('delete-item', this.id);
    },
    emitChanges(changes: Partial<ConditionOptions>): void {
      this.options = {
        ...this.options,
        ...changes,
      };

      // We don't want to pass the Proxy object so we create a new object
      this.$emit('condition-changed', this.id, { ...this.options });
      this.editing = false;
    },
    getFieldInfo(): CollectibleFieldModel | undefined {
      return (this.availableFields as CollectibleFieldModel[]).find(
        (field) => field.code === this.options.field
      );
    },
  },
  computed: {
    fieldTypeComponent(): string | undefined {
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
  components: {
    BooleanCondition,
    DateCondition,
    NumberCondition,
    TagCondition,
    TextCondition,
  },
});
</script>

<style lang="scss">
.criteria-condition {
  display: flex;
  flex-direction: row;
}
</style>
