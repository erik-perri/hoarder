<template>
  <span class="match-option-text">
    <span v-if="editing">
      <select v-model="currentComparison" ref="comparison">
        <option>equals</option>
        <option>contains</option>
        <option>starts with</option>
        <option>ends with</option>
        <option>does not equal</option>
        <option>does not contains</option>
        <option>does not start with</option>
        <option>does not end with</option>
      </select>
      <input type="text" v-model="currentValue" ref="value" />
      <button @click.prevent="validateAndSave">Save</button>
    </span>
    <span v-else>{{ currentComparison }}&nbsp;{{ currentValue }}</span>
  </span>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import BaseCondition from './BaseCondition.vue';

export default defineComponent({
  extends: BaseCondition,
  methods: {
    validateAndSave() {
      if (!this.currentComparison) {
        (this.$refs.comparison as HTMLElement)?.focus();
      } else if (!this.currentValue) {
        (this.$refs.value as HTMLElement)?.focus();
      } else {
        this.emitSave();
      }
    },
  },
});
</script>
