<template>
  <div>
    <label :for="id">
      <slot></slot>
      <input
        type="checkbox"
        :id="id"
        :name="name"
        v-bind:value="value"
        v-on:input="$emit('input', $event.target.checked)"
      />
      {{ label }}
    </label>
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
    label: {
      type: String,
      required: true,
    },
    value: {
      type: Boolean,
      required: true,
    },
  },
});
</script>
