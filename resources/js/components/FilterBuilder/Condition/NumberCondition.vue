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
import { defineComponent } from 'vue';
import BaseCondition from './BaseCondition.vue';

export default defineComponent({
  extends: BaseCondition,
  data() {
    return {
      comparisonOptions: {
        number_equals: '=',
        number_greater_than: '&gt;',
        number_greater_than_or_equal: '&gt;=',
        number_less_than: '&lt;',
        number_less_than_or_equal: '&lt;=',
      } as Record<string, string>,
    };
  },
});
</script>
