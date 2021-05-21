<template>
  <span class="match-option-number">
    <span v-if="editing">
      <select v-model="currentComparison">
        <option>=</option>
        <option>&gt;</option>
        <option>&gt;=</option>
        <option>&lt;</option>
        <option>&lt;=</option>
      </select>
      <input type="number" v-model="currentValue" />
    </span>
    <span v-else>{{ currentComparison }}&nbsp;{{ currentValue }}</span>
  </span>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import BaseCondition from './BaseCondition.vue';

export default defineComponent({
  extends: BaseCondition,
});
</script>
