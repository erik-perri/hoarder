<template>
  <div class="field-editor">
    <ul>
      <li>
        <FieldInput
          field-identifier="name"
          field-name="Name"
          input-type="text"
          :is-required="true"
          :is-disabled="true"
        />
      </li>
      <li v-for="field in this.fields" :key="field.code">
        <div class="field-item">
          <FieldInput
            :field-identifier="field.code"
            :field-name="field.name"
            :input-type="field.input_type"
            :is-required="!!field.is_required"
            :is-disabled="field.is_removed"
            :is-new="field.is_new"
            v-bind:class="{ removed: field.is_removed }"
            @removeField="removeField"
            @updateField="updateField"
          />

          <a
            href="#"
            @click.prevent="restoreField(field.code)"
            v-if="field.is_removed"
          >
            Restore
          </a>
        </div>
      </li>
    </ul>

    <a href="#" @click.prevent="addField">Add</a>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { v4 as uuid } from 'uuid';
import FieldInput from './FieldInput.vue';
import { FieldEditorItem, FieldEditorItems, FieldInputUpdate } from './types';

export default Vue.extend({
  components: { FieldInput },
  data() {
    const fields: FieldEditorItems = [];

    (this.items as FieldEditorItems).forEach((item) => {
      fields.push({
        ...item,
        is_new: false,
        is_removed: false,
      });
    });

    return { fields };
  },
  props: {
    inputName: {
      type: String,
      required: true,
    },
    items: {
      type: Array,
      required: true,
    },
  },
  methods: {
    addField(): void {
      this.fields.push({
        name: '',
        code: uuid(),
        input_type: 'text',
        input_options: {},
        is_required: false,

        is_new: true,
        is_removed: false,
      });
    },
    findField(identifier: string): FieldEditorItem {
      const field = this.fields.find(
        (item: FieldEditorItem) => item.code === identifier
      );
      if (!field) {
        throw new Error('Unknown field specified');
      }
      return field;
    },
    restoreField(identifier: string): void {
      const field = this.findField(identifier);

      field.is_removed = false;

      this.emitFields();
    },
    removeField(identifier: string): void {
      const field = this.findField(identifier);
      if (field.is_new) {
        this.fields = this.fields.filter((item) => item !== field);
      } else {
        field.is_removed = true;
      }

      this.emitFields();
    },
    updateField(identifier: string, values: FieldInputUpdate): void {
      const field = this.findField(identifier);

      field.name = values.fieldName;
      field.input_type = values.inputType;
      field.is_required = values.isRequired;

      this.emitFields();
    },
    emitFields(): void {
      this.$emit('update', JSON.parse(JSON.stringify(this.fields)));
    },
  },
});
</script>

<style lang="scss">
.field-editor {
  .field-item {
    display: flex;
    flex-direction: row;
  }
  .removed {
    opacity: 0.4;
  }
}
</style>
