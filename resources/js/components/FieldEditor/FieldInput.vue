<template>
  <div v-bind:class="{ 'field-input': true, removed: removedValue }">
    <div class="field-input-group">
      <label :for="`display_name-${code}`">Name</label>
      <input
        :id="`display_name-${code}`"
        :name="`${inputName}[${code}][display_name]`"
        type="text"
        :value="name"
        :disabled="isDisabled || removedValue"
        required
      />
    </div>

    <div class="field-input-group">
      <label :for="`input_type-${code}`">Input Type</label>
      <select
        v-model="inputTypeValue"
        :id="`input_type-${code}`"
        :name="`${inputName}[${code}][input_type]`"
        :disabled="isDisabled || removedValue"
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
      <label :for="`required-${code}`">Required</label>
      <input
        :id="`required-${code}`"
        :name="`${inputName}[${code}][is_required]`"
        type="checkbox"
        value="1"
        :checked="requiredValue"
        :disabled="isDisabled || removedValue"
      />
    </div>

    <div v-if="!isDisabled">
      <input
        type="hidden"
        :value="removedValue ? 1 : 0"
        :name="`${inputName}[${code}][is_removed]`"
      />
      <a
        href="#"
        @click.prevent="this.removedValue = !this.removedValue"
        v-if="!removedValue"
      >
        Remove
      </a>
      <a
        href="#"
        @click.prevent="this.removedValue = !this.removedValue"
        v-else
      >
        Undo
      </a>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  data() {
    return {
      fieldTypes: {
        text: 'Text',
        textarea: 'Text area',
        url: 'URL',
        date: 'Date',
        number: 'Number',
        tags: 'Tags',
        boolean: 'Boolean', // TODO Yes/No?
      },
      inputTypeValue: this.inputType,
      requiredValue: this.isRequired,
      removedValue: false,
    };
  },
  emits: ['removeField'],
  props: {
    inputName: {
      type: String,
      required: true,
    },
    code: {
      type: String,
      required: true,
    },
    name: {
      type: String,
      required: true,
    },
    inputType: {
      type: String,
      required: true,
    },
    isRequired: Boolean,
    isDisabled: Boolean,
  },
});
</script>

<style lang="scss">
.field-input {
  display: flex;
  flex-direction: row;

  &.removed {
    .field-input-group {
      opacity: 0.4;
    }
  }
}
</style>
