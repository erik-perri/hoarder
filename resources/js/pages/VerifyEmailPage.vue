<template>
  <div>
    <h2>Verify Email</h2>
    {{ message }}
    <p>
      Thanks for signing up! Before getting started, could you verify your email
      address by clicking on the link we just emailed to you? If you didn't
      receive the email, we will gladly send you another.
    </p>
    <form>
      <button type="submit" :disabled="loading" @click.prevent="submit">
        Resend Verification Email
      </button>
    </form>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { ApiResponse } from '../api/types';
import axios, { AxiosError, AxiosResponse } from 'axios';

export default Vue.extend({
  data() {
    return {
      loading: false,
      message: undefined as string | undefined,
    };
  },
  methods: {
    async submit() {
      this.loading = true;

      await axios
        .post('/verify-email')
        .then((response: AxiosResponse<ApiResponse>) => {
          this.message = response.data.message;
        })
        .catch((error: AxiosError) => {
          if (error.response?.status === 429) {
            this.message = 'Please wait before trying again.';
          } else {
            this.message = error.message;
          }
        });

      this.loading = false;
    },
  },
});
</script>
