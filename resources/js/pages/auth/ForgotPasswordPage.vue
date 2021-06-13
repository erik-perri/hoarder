<template>
  <div>
    <h2>Forgot Password</h2>

    <p>
      Forgot your password? No problem. Just let us know your email address and
      we will email you a password reset link that will allow you to choose a
      new one.
    </p>

    <form method="post" id="forgot-password">
      <div v-if="message">
        {{ message }}
      </div>

      <EmailInput
        id="email"
        name="email"
        label="Email"
        v-model="email"
        :errors="errors.email"
      />

      <div>
        <button type="submit" :disabled="loading" @click.prevent="submit">
          Forgot Password
        </button>
      </div>
    </form>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { EmailInput } from '../../components/Forms';
import { forgotPassword } from '../../api/user';

export default Vue.extend({
  data() {
    return {
      loading: false as boolean,
      message: undefined as string | undefined,
      errors: {} as Record<string, string[]>,
      email: '' as string,
    };
  },
  methods: {
    async submit(): Promise<void> {
      this.message = undefined;
      this.errors = {};
      this.loading = true;

      const response = await forgotPassword(this.email);
      if (response.status === 'success') {
        this.message = response.message;
      } else {
        this.message = response.message;
        this.errors = response.errors || {};
      }

      this.loading = false;
    },
  },
  components: { EmailInput },
});
</script>
