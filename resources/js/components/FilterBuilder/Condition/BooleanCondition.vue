<template>
  <span class="match-option-boolean">
    <span v-if="editing">
      <select v-model="currentComparison" ref="comparison" required>
        <option
          v-for="(label, value) in comparisonOptions"
          :value="value"
          :key="value"
        >
          {{ label }}
        </option>
      </select>
      <select v-model="currentValue" ref="value" required>
        <option>true</option>
        <option>false</option>
        <option>unset</option>
      </select>
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
        boolean_is: 'is',
        boolean_is_not: 'is not',
      } as Record<string, string>,
    };
  },
});
</script>
