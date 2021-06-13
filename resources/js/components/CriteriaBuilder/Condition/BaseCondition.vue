<script lang="ts">
import Vue, { PropType } from 'vue';
import { v4 as uuid } from 'uuid';

export default Vue.extend({
  props: {
    editing: Boolean as PropType<boolean>,
    comparison: {
      type: String as PropType<string>,
      required: true,
    },
    value: {
      type: String as PropType<string>,
      required: true,
    },
  },
  data() {
    return {
      id: uuid() as string,
      currentComparison: this.comparison as string,
      currentValue: this.value as string,
    };
  },
  methods: {
    emitSave(): void {
      this.$emit('save', {
        comparison: this.currentComparison,
        value: this.currentValue,
      });
    },
    validateAndSave(): void {
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
