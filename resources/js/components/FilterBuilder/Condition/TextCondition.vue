<template>
  <span class="match-option-text">
    <span v-if="editing">
      <select v-model="currentComparison" ref="comparison" required>
        <option v-for="(label, value) in comparisonOptions" :value="value">
          {{ label }}
        </option>
      </select>
      <input type="text" v-model="currentValue" ref="value" required />
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
        text_equals: 'equals',
        text_contains: 'contains',
        text_starts_with: 'starts with',
        text_ends_with: 'ends with',
        text_does_not_equal: 'does not equal',
        text_does_not_contain: 'does not contains',
        text_does_not_start_with: 'does not start',
        text_does_not_end_with: 'does not end with',
      } as Record<string, string>,
    };
  },
});
</script>
