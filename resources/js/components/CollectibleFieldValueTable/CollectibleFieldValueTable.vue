<template>
  <table class="field-value-table">
    <tbody>
      <slot />

      <tr
        v-for="field in fields"
        :key="field.code"
        v-if="field.code !== 'name'"
      >
        <th>{{ field.name }}</th>
        <td>
          <span v-if="values[field.code] !== undefined">
            <span
              v-if="field.name === 'Image URL' || field.name === 'Logo URL'"
            >
              <a :href="values[field.code]" target="_blank">
                <img
                  :src="values[field.code]"
                  style="max-height: 200px; display: block"
                  :alt="field.name"
                />
                {{ values[field.code] }}
              </a>
            </span>
            <span v-else>
              {{ values[field.code] }}
            </span>
          </span>
          <span v-else class="unset">Unset</span>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script lang="ts">
import Vue from 'vue';
import { TextInput } from '../../components/Forms';
import { FieldEditor } from '../../components/FieldEditor';
import {
  CollectibleFieldModel,
  CollectibleFieldValues,
} from '../../api/collectibles';

interface Props {
  fields: Array<CollectibleFieldModel>;
  values: CollectibleFieldValues;
}

export default Vue.extend<{}, {}, {}, Props>({
  components: { FieldEditor, TextInput },
  props: {
    fields: {
      type: Array,
      required: true,
    },
    values: {
      type: Object,
      required: true,
    },
  },
});
</script>

<style lang="scss">
.field-value-table {
  .unset {
    color: #888;
  }
}
</style>
