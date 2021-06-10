<script lang="ts">
import Vue from 'vue';
import TextInput from './TextInput.vue';

interface Data {
  attributes: Record<string, string>;
}

interface Methods {}

interface Computed {}

interface Props {}

export default Vue.extend<Data, Methods, Computed, Props>({
  extends: TextInput,
  data() {
    return {
      attributes: {
        type: 'email',
      },
    };
  },
});
</script>
