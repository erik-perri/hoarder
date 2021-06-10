<template>
  <span class="match-option-number">
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

      <label :for="`value-${this.id}`">Number</label>
      <input
        type="number"
        v-model="currentValue"
        ref="value"
        :id="`value-${this.id}`"
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
        number_equals: 'equals',
        number_greater_than: 'greater than',
        number_greater_than_or_equal: 'greater than or equal',
        number_less_than: 'less than',
        number_less_than_or_equal: 'less than or equal',
      },
    };
  },
});
</script>
