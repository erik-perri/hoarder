<template>
  <div>
    <h2>{{ item.name }}</h2>

    <CollectibleFieldValueTable
      :fields="collectible.item_fields"
      :values="item.field_values"
    >
      <tr>
        <th>Collectible</th>
        <td>
          <router-link
            :to="{
              name: 'collectibles.show',
              params: { collectible: collectible.id },
            }"
          >
            {{ collectible.name }}
          </router-link>
        </td>
      </tr>
      <tr>
        <th>Category</th>
        <td>
          <router-link
            :to="{
              name: 'categories.show',
              params: { category: category.id },
            }"
          >
            {{ category.name }}
          </router-link>
        </td>
      </tr>
    </CollectibleFieldValueTable>
  </div>
</template>

<script lang="ts">
import Vue, { PropType } from 'vue';
import {
  Collectible,
  CollectibleCategory,
  CollectibleItem,
} from '../../api/collectibles';
import { CollectibleFieldValueTable } from '../../components/CollectibleFieldValueTable';

export default Vue.extend({
  props: {
    collectible: {
      type: Object as PropType<Collectible>,
      required: true,
    },
    category: {
      type: Object as PropType<CollectibleCategory>,
      required: true,
    },
    item: {
      type: Object as PropType<CollectibleItem>,
      required: true,
    },
  },
  computed: {
    isLoggedIn: function (): boolean {
      return this.$store.getters['auth/isLoggedIn'];
    },
  },
  components: { CollectibleFieldValueTable },
});
</script>
