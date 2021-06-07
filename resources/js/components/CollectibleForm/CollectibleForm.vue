<template>
  <form method="post" id="collectible-form" @submit.prevent="submit">
    <TextInput
      id="name"
      name="name"
      label="Name"
      v-model="collectibleNameValue"
      :errors="errors.collectible ? errors.collectible.name : null"
    />

    <h3>Category Fields</h3>
    <FieldEditor
      inputName="category_fields"
      :items="categoryFieldsValue"
      @update="(fields) => updateCategoryFields(fields)"
    />
    {{ errors.categoryFields /* TODO */ }}

    <h3>Item Fields</h3>
    <FieldEditor
      inputName="item_fields"
      :items="itemFieldsValue"
      @update="(fields) => updateItemFields(fields)"
    />
    {{ errors.itemFields }}

    <div>
      <button type="submit">Save</button>
    </div>
  </form>
</template>

<script lang="ts">
import Vue from 'vue';
import { TextInput } from '../../components/Forms';
import { FieldEditor } from '../../components/FieldEditor';
import { CollectibleFieldModel } from '../../api/collectibles';

export default Vue.extend({
  components: { FieldEditor, TextInput },
  props: {
    collectible: {
      type: Object,
      required: true,
    },
    categoryFields: {
      type: Array,
      required: true,
    },
    itemFields: {
      type: Array,
      required: true,
    },
    handleSubmit: {
      type: Function,
      required: true,
    },
  },
  data() {
    return {
      collectibleNameValue: this.collectible.name || '',
      categoryFieldsValue: this.categoryFields as Array<CollectibleFieldModel>,
      itemFieldsValue: this.itemFields as Array<CollectibleFieldModel>,
      errors: {},
    };
  },
  methods: {
    updateCategoryFields(fields: Array<CollectibleFieldModel>) {
      this.categoryFieldsValue = fields;
    },
    updateItemFields(fields: Array<CollectibleFieldModel>) {
      this.itemFieldsValue = fields;
    },
    async submit() {
      this.errors =
        (await this.handleSubmit(
          {
            ...this.collectible,
            name: this.collectibleNameValue,
          },
          JSON.parse(JSON.stringify(this.categoryFieldsValue)),
          JSON.parse(JSON.stringify(this.itemFieldsValue))
        )) || {};
    },
  },
});
</script>
