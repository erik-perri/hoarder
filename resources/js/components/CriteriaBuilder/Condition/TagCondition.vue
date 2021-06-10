<template>
  <span class="match-option-tag">
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

      <label :for="`value-${this.id}`">Tag</label>
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
        tag_contains_only: 'contains only',
        tag_contains_any: 'contains any',
        tag_contains_all: 'contains all',
      },
    };
  },
});
</script>
