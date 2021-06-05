<script lang="ts">
import Vue from 'vue';
import { v4 as uuid } from 'uuid';

export default Vue.extend({
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
  data() {
    return {
      id: uuid(),
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
    validateAndSave() {
      if (this.$refs.comparison && !this.currentComparison) {
        (this.$refs.comparison as HTMLElement)?.focus();
      } else if (this.$refs.value && !this.currentValue) {
        (this.$refs.value as HTMLElement)?.focus();
      } else {
        this.emitSave();
      }
    },
  },
});
</script>
