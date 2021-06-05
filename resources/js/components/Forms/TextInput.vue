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
import Vue from 'vue';

export default Vue.extend({
  props: {
    id: {
      type: String,
      required: true,
    },
    name: {
      type: String,
      required: true,
    },
    value: {
      type: String,
      required: true,
    },
    disabled: Boolean,
    label: String,
    errors: Array,
  },
  data() {
    return {
      attributes: {
        type: 'text',
      },
    };
  },
});
</script>
