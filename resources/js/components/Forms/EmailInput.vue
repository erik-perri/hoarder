<script lang="ts">
import Vue from 'vue';
import TextInput from './TextInput.vue';

export default Vue.extend({
  extends: TextInput,
  data() {
    return {
      attributes: {
        type: 'email',
      } as Record<string, string>,
    };
  },
});
</script>
