<template>
  <div class="match-option-boolean">
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

      <label :for="`value-${this.id}`">Value</label>
      <select
        :id="`value-${this.id}`"
        v-model="currentValue"
        ref="value"
        required
      >
        <option>true</option>
        <option>false</option>
        <option>unset</option>
      </select>

      <button @click.prevent="validateAndSave">Save</button>
    </div>
    <div v-else>
      {{ comparisonOptions[currentComparison] }}&nbsp;
      {{ currentValue }}
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import BaseCondition from './BaseCondition.vue';

export default Vue.extend({
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
