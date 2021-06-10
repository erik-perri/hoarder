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
import Vue from 'vue';
import { CollectibleFieldModel } from '../../api/collectibles';
import { ConditionOptions } from './types';
import BooleanCondition from './Condition/BooleanCondition.vue';
import DateCondition from './Condition/DateCondition.vue';
import NumberCondition from './Condition/NumberCondition.vue';
import TagCondition from './Condition/TagCondition.vue';
import TextCondition from './Condition/TextCondition.vue';

interface Data {
  editing: boolean;
  options: ConditionOptions;
  fieldMapping: Record<string, string>;
}

interface Methods {
  deleteItem: () => void;
  emitChanges: (changes: Partial<ConditionOptions>) => void;
  getFieldInfo: () => CollectibleFieldModel | undefined;
}

interface Computed {
  fieldTypeComponent: string | undefined;
  fieldName: string | undefined;
}

interface Props {
  id: string;
  startEditing: boolean;
  availableFields: Array<CollectibleFieldModel>;
  field: string;
  comparison: string;
  value: string;
}

export default Vue.extend<Data, Methods, Computed, Props>({
  props: {
    id: String,
    startEditing: Boolean,
    availableFields: Array,
    field: String,
    comparison: String,
    value: String,
  },
  data() {
    return {
      editing: this.startEditing || false,
      options: {
        field: this.field || '',
        comparison: this.comparison,
        value: this.value,
      },
      fieldMapping: {
        text: 'TextCondition',
        textarea: 'TextCondition',
        url: 'TextCondition',
        date: 'DateCondition',
        number: 'NumberCondition',
        tags: 'TagCondition',
        boolean: 'BooleanCondition',
      },
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
      this.$emit('delete-item', this.id);
    },
    emitChanges(changes: Partial<ConditionOptions>) {
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
