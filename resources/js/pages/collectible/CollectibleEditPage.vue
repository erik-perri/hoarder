<template>
  <div>
    <h2>
      {{
        $route.params.collectible ? 'Edit Collectible' : 'Create Collectible'
      }}
    </h2>

    <CollectibleForm
      v-if="collectible"
      :collectible="collectible"
      :categoryFields="JSON.parse(JSON.stringify(categoryFields))"
      :itemFields="JSON.parse(JSON.stringify(itemFields))"
      :handle-submit="submit"
    />
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import {
  CollectibleForm,
  CollectibleFormSubmitHandlerReturn,
} from '../../components/CollectibleForm';
import {
  Collectible,
  CollectibleFieldEntityType,
  CollectibleFieldModel,
  getCollectible,
  storeOrUpdateCollectible,
} from '../../api/collectibles';
import { FieldEditorItem } from '../../components/FieldEditor';

export default Vue.extend({
  data() {
    return {
      isLoading: false,
      errors: {},
      collectible: null as Collectible | null,
      categoryFields: [] as Array<CollectibleFieldModel>,
      itemFields: [] as Array<CollectibleFieldModel>,
    };
  },
  async created() {
    await this.refreshCollectible();
  },
  watch: {
    $route: 'refreshCollectible',
  },
  methods: {
    async refreshCollectible() {
      if (!this.$route.params.collectible) {
        this.collectible = {
          name: '',
        } as Collectible;
        this.categoryFields = [];
        this.itemFields = [];
        return;
      }

      const id = parseInt(this.$route.params.collectible, 10);
      if (id) {
        this.isLoading = true;
        this.errors = {};

        const response = await getCollectible(id);
        if (response.status === 'success' && response.data) {
          this.collectible = response.data.collectible;
          this.categoryFields = response.data.categoryFields;
          this.itemFields = response.data.itemFields;
        } else {
          this.errors = response.errors || {};
        }

        this.isLoading = false;
      }
    },
    async submit(
      collectible: Collectible,
      categoryFields: Array<FieldEditorItem>,
      itemFields: Array<FieldEditorItem>
    ): Promise<CollectibleFormSubmitHandlerReturn> {
      const response = await storeOrUpdateCollectible(
        collectible,
        categoryFields.map((f) => this.convertEditorItemToField(f, 'category')),
        itemFields.map((f) => this.convertEditorItemToField(f, 'item'))
      );

      if (response.status === 'success') {
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
