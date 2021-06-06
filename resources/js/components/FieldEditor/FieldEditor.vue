<template>
  <div class="field-editor">
    <ul>
      <li>
        <FieldInput
          code="name"
          :input-name="inputName"
          name="Name"
          input-type="text"
          :is-required="true"
          :is-disabled="true"
        />
      </li>
      <li v-for="field in this.fields" :key="field.code">
        <FieldInput
          :code="field.code"
          :input-name="inputName"
          :name="field.name"
          :input-type="field.input_type"
          :is-required="!!field.is_required"
          @removeField="removeField"
        />
      </li>
    </ul>

    <a href="#" @click.prevent="addField">Add</a>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { v4 as uuid } from 'uuid';
import FieldInput from './FieldInput.vue';
import { FieldEditorItem, FieldEditorItems } from './types';

export default Vue.extend({
  components: { FieldInput },
  data() {
    return {
      fields: this.items as FieldEditorItems,
    };
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
      });
    },
    removeField(code: string): void {
      this.fields = this.fields.filter(
        (item: FieldEditorItem) => item.code !== code
      );
    },
  },
});
</script>
