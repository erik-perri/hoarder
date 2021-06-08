<template>
  <div class="field-input">
    <div v-if="isEditing && isEditable">
      <div class="field-input-group">
        <label :for="`fieldName-${fieldIdentifier}`">Name</label>
        <input
          ref="fieldName"
          type="text"
          :id="`fieldName-${fieldIdentifier}`"
          v-model="fieldNameValue"
          required
        />
      </div>

      <div class="field-input-group">
        <label :for="`inputType-${fieldIdentifier}`">Input Type</label>
        <select
          ref="inputType"
          v-model="inputTypeValue"
          :id="`inputType-${fieldIdentifier}`"
          required
        >
          <option
            v-for="(label, value) in fieldTypes"
            :value="value"
            :key="value"
          >
            {{ label }}
          </option>
        </select>
      </div>

      <div class="field-input-group">
        <label :for="`required-${fieldIdentifier}`">Required</label>
        <input
          :id="`required-${fieldIdentifier}`"
          type="checkbox"
          value="1"
          v-model="isRequiredValue"
        />
      </div>
    </div>
    <div v-else class="field-input-info">
      {{ fieldNameValue }}
      ({{ `${inputTypeValue}${isRequiredValue ? '; Required' : ''}` }})
    </div>

    <div v-if="!isDisabled && isEditable">
      <a href="#" @click.prevent="isEditing = true" v-if="!isEditing">Edit</a>

      <a href="#" @click.prevent="save" v-if="isEditing">Save</a>

      <a href="#" @click.prevent="remove" v-if="isEditing">Remove</a>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { FieldInputUpdate } from './types';

export default Vue.extend({
  props: {
    fieldIdentifier: {
      type: String,
      required: true,
    },
    fieldName: {
      type: String,
      required: true,
    },
    inputType: {
      type: String,
      required: true,
    },
    isEditable: Boolean,
    isRequired: Boolean,
    isDisabled: Boolean,
    isNew: Boolean,
  },
  data() {
    return {
      isEditing: this.isNew,
      fieldTypes: {
        text: 'Text',
        textarea: 'Text area',
        url: 'URL',
        date: 'Date',
        number: 'Number',
        tags: 'Tags',
        boolean: 'Boolean', // TODO Yes/No?
      },
      fieldNameValue: this.fieldName,
      inputTypeValue: this.inputType,
      isRequiredValue: this.isRequired,
    };
  },
  methods: {
    save() {
      if (!this.fieldNameValue) {
        (this.$refs.fieldName as HTMLElement)?.focus();
        return;
      }

      if (!this.inputTypeValue) {
        (this.$refs.inputType as HTMLElement).focus();
        return;
      }

      this.isEditing = false;

      this.emitUpdate();
    },
    remove() {
      this.isEditing = false;

      this.emitRemove();
    },
    emitRemove() {
      this.$emit('removeField', this.fieldIdentifier);
    },
    emitUpdate() {
      this.$emit('updateField', this.fieldIdentifier, {
        fieldName: this.fieldNameValue,
        inputType: this.inputTypeValue,
        isRequired: this.isRequiredValue,
      } as FieldInputUpdate);
    },
  },
});
</script>

<style lang="scss">
.field-input {
  display: flex;
  flex-direction: row;
}
</style>
