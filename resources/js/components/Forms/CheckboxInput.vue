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
    label: {
      type: String as PropType<string>,
      required: true,
    },
    value: {
      type: Boolean as PropType<boolean>,
      required: true,
    },
  },
});
</script>
