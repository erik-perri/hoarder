<template>
  <div>
    <h2>Verify Email</h2>
    {{ message }}
    <p>
      Thanks for signing up! Before getting started, could you verify your email
      address by clicking on the link we just emailed to you? If you didn't
      receive the email, we will gladly send you another.
    </p>
    <form method="post" id="verify-email">
      <button type="submit" :disabled="loading" @click.prevent="submit">
        Resend Verification Email
      </button>
    </form>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { sendEmailVerification } from '../api/user';

export default Vue.extend({
  data() {
    return {
      loading: false,
      message: undefined as string | undefined,
    };
  },
  methods: {
    async submit() {
      this.message = undefined;
      this.loading = true;

      const response = await sendEmailVerification();
      this.message = response.message;

      this.loading = false;
    },
  },
});
</script>
