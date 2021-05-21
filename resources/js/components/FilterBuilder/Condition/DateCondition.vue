<template>
  <span class="match-option-date">
    <span v-if="editing">
      <select role="combobox" v-model="currentComparison">
        <option>on</option>
        <option>before</option>
        <option>after</option>
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
