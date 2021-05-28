<template>
  <div class="match-option-date">
    <div v-if="editing">
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

      <label :for="`value-${this.id}`">Date</label>
      <input
        :id="`value-${this.id}`"
        type="date"
        v-model="currentValue"
        ref="value"
        pattern="\d{4}-\d{2}-\d{2}"
        required
      />

      <button @click.prevent="validateAndSave">Save</button>
    </div>
    <div v-else>
      {{ comparisonOptions[currentComparison] }}&nbsp;
      {{ currentValue }}
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import BaseCondition from './BaseCondition.vue';

export default defineComponent({
  extends: BaseCondition,
  data() {
    return {
      comparisonOptions: {
        date_on: 'on',
        date_before: 'before',
        date_after: 'after',
        date_on_or_before: 'on or before',
        date_on_or_after: 'on or after',
      } as Record<string, string>,
    };
  },
});
</script>
