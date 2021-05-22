<template>
  <span class="match-option-number">
    <span v-if="editing">
      <select v-model="currentComparison" ref="comparison" required>
        <option v-for="(label, value) in comparisonOptions" :value="value">
          {{ label }}
        </option>
      </select>
      <input type="number" v-model="currentValue" ref="value" required />
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
