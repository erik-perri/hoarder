<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  abstract: true,
  props: {
    editing: Boolean,
    comparison: {
      type: String,
      required: true,
    },
    value: {
      type: String,
      required: true,
    },
  },
  emits: ['save'],
  data() {
    return {
      currentComparison: this.comparison,
      currentValue: this.value,
    };
  },
  methods: {
    emitSave() {
      this.$emit('save', {
        comparison: this.currentComparison,
        value: this.currentValue,
      });
    },
  },
});
</script>
