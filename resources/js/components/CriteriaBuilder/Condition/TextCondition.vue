<template>
  <span class="match-option-text">
    <span v-if="editing">
      <label :for="`comparison-${this.id}`">Comparison</label>
      <select
        :id="`comparison-${this.id}`"
        v-model="currentComparison"
        ref="comparison"
        required
      >
        <option
          v-for="(label, value) in comparisonOptions"
          :value="value"
          :key="value"
        >
          {{ label }}
        </option>
      </select>

      <label :for="`value-${this.id}`">Text</label>
      <input
        :id="`value-${this.id}`"
        type="text"
        v-model="currentValue"
        ref="value"
        required
      />

      <button @click.prevent="validateAndSave">Save</button>
    </span>
    <span v-else>
      {{ comparisonOptions[currentComparison] }}&nbsp;
      {{ currentValue }}
    </span>
  </span>
</template>

<script lang="ts">
import Vue from 'vue';
import BaseCondition from './BaseCondition.vue';

interface Data {
  comparisonOptions: Record<string, string>;
}

interface Methods {}

interface Computed {}

interface Props {}

export default Vue.extend<Data, Methods, Computed, Props>({
  extends: BaseCondition,
  data() {
    return {
      comparisonOptions: {
        text_equals: 'equals',
        text_contains: 'contains',
        text_starts_with: 'starts with',
        text_ends_with: 'ends with',
        text_does_not_equal: 'does not equal',
        text_does_not_contain: 'does not contains',
        text_does_not_start_with: 'does not start',
        text_does_not_end_with: 'does not end with',
      },
    };
  },
});
</script>
