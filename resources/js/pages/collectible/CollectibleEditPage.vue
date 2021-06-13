<template>
  <div>
    <h2>
      {{ collectible ? 'Edit Collectible' : 'Create Collectible' }}
    </h2>

    <CollectibleForm
      :collectible="collectible || { name: '' }"
      :handle-submit="submit"
    />
  </div>
</template>

<script lang="ts">
import Vue, { PropType } from 'vue';
import {
  CollectibleForm,
  CollectibleFormSubmitHandlerReturn,
} from '../../components/CollectibleForm';
import {
  Collectible,
  CollectibleFieldEntityType,
  CollectibleFieldModel,
  storeOrUpdateCollectible,
} from '../../api/collectibles';
import { FieldEditorItem } from '../../components/FieldEditor';

export default Vue.extend({
  props: {
    collectible: Object as PropType<Collectible>,
  },
  data() {
    return {
      isLoading: false as boolean,
      errors: {} as Record<string, string[]>,
    };
  },
  methods: {
    async submit(
      collectible: Collectible,
      categoryFields: Array<FieldEditorItem>,
      itemFields: Array<FieldEditorItem>
    ): Promise<CollectibleFormSubmitHandlerReturn> {
      collectible.category_fields = categoryFields.map((f) =>
        this.convertEditorItemToField(f, 'category')
      );
      collectible.item_fields = itemFields.map((f) =>
        this.convertEditorItemToField(f, 'item')
      );
      const response = await storeOrUpdateCollectible(collectible);

      if (response.status === 'success') {
        this.$emit('refresh-collectible');
        await this.$router.push({
          name: 'collectibles.show',
          params: { collectible: response.data.collectible.id.toString(10) },
        });
      } else {
        // TODO The entire way errors are handled for the collectible fields need to be redone.

        const errors = {
          collectible: response.errors,
          categoryFields: {} as Record<string, string[]>,
          itemFields: {} as Record<string, string[]>,
        };

        if (response.errors) {
          Object.keys(response.errors).forEach((key) => {
            const fieldErrors = response.errors
              ? response.errors[key]
              : ['Unknown error.'];

            if (key.match(/^item_fields/)) {
              errors.itemFields[key] = fieldErrors;
            } else if (key.match(/^category_fields/)) {
              errors.categoryFields[key] = fieldErrors;
            }
          });
        }

        return errors;
      }
    },
    convertEditorItemToField(
      item: FieldEditorItem,
      entityType: CollectibleFieldEntityType
    ): CollectibleFieldModel {
      return {
        ...item,
        entity_type: entityType,
      };
    },
  },
  components: { CollectibleForm },
});
</script>
