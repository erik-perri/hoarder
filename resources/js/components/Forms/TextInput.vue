<template>
  <div>
    <slot></slot>

    <label v-if="label" :for="id">{{ label }}</label>
    <input
      v-bind:type="attributes.type"
      :id="id"
      :name="name"
      :value="value"
      :disabled="disabled"
      v-bind:value="value"
      v-on:input="$emit('input', $event.target.value)"
    />

    <div v-if="errors">{{ errors.join(' ') }}</div>
  </div>
</template>

<script lang="ts">
import Vue, { PropType } from 'vue';

export default Vue.extend({
  props: {
    id: {
      type: String as PropType<string>,
      required: true,
    },
    name: {
      type: String as PropType<string>,
      required: true,
    },
    value: {
      type: String as PropType<string>,
      required: true,
    },
    disabled: Boolean as PropType<boolean>,
    label: String as PropType<string>,
    errors: Array as PropType<Array<string>>,
  },
  data() {
    return {
      attributes: {
        type: 'text',
      } as Record<string, string>,
    };
  },
});
</script>
