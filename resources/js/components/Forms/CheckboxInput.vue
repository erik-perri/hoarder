<template>
  <div>
    <label :for="id">
      <slot></slot>
      <input
        type="checkbox"
        :id="id"
        :name="name"
        :checked="value"
        v-bind:value="value"
        v-on:input="$emit('input', $event.target.checked)"
      />
      {{ label }}
    </label>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';

interface Data {}

interface Methods {}

interface Computed {}

interface Props {
  id: string;
  name: string;
  label: string;
  value: boolean;
}

export default Vue.extend<Data, Methods, Computed, Props>({
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
