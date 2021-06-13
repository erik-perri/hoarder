<script lang="ts">
import Vue from 'vue';
import TextInput from './TextInput.vue';

export default Vue.extend({
  extends: TextInput,
  data() {
    return {
      attributes: {
        type: 'password',
      } as Record<string, string>,
    };
  },
});
</script>
