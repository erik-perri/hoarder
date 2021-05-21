<template>
  <span class="match-option-boolean">
    <span v-if="editing">
      <select role="combobox" v-model="currentValue" ref="value">
        <option>true</option>
        <option>false</option>
        <option>unset</option>
      </select>
      <button @click.prevent="validateAndSave">Save</button>
    </span>
    <span v-else>{{ currentValue }}</span>
  </span>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import BaseCondition from './BaseCondition.vue';

export default defineComponent({
  extends: BaseCondition,
  data() {
    return {
      currentComparison: 'bool',
    };
  },
  methods: {
    validateAndSave() {
      if (!this.currentValue) {
        (this.$refs.value as HTMLElement)?.focus();
      } else {
        this.emitSave();
      }
    },
  },
});
</script>
