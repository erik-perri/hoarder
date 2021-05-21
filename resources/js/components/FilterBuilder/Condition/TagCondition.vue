<template>
  <span class="match-option-tag">
    <span v-if="editing">
      <select v-model="currentComparison">
        <option>contains only</option>
        <option>contains any</option>
        <option>contains all</option>
      </select>
      <input type="text" v-model="currentValue" />
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
