<template>
  <span class="match-option-tag">
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
        tag_contains_only: 'contains only',
        tag_contains_any: 'contains any',
        tag_contains_all: 'contains all',
      } as Record<string, string>,
    };
  },
});
</script>
