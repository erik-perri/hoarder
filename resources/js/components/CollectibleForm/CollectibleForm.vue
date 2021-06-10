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
  props: {
    collectible: {
      type: Object,
      required: true,
    },
    handleSubmit: {
      type: Function,
      required: true,
    },
  },
  data() {
    const categoryFields = JSON.parse(
      JSON.stringify(
        this.collectible?.category_fields || [
          {
            name: 'Name',
            code: 'name',
            is_required: true,
            input_type: 'text',
          },
        ]
      )
    );
    const itemFields = JSON.parse(
      JSON.stringify(
        this.collectible?.item_fields || [
          {
            name: 'Name',
            code: 'name',
            is_required: true,
            input_type: 'text',
          },
        ]
      )
    );
    return {
      collectibleNameValue: this.collectible.name || '',
      categoryFieldsValue: categoryFields as Array<CollectibleFieldModel>,
      itemFieldsValue: itemFields as Array<CollectibleFieldModel>,
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
  components: { FieldEditor, TextInput },
});
</script>
