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
      <div v-if="errors.message">
        {{ errors.message }}
      </div>

      <EmailInput
        id="email"
        name="email"
        label="Email"
        v-model="email"
        :errors="errors.errors.email"
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
import { EmailInput } from '../components/Forms';
import { ApiResponse, FormErrors } from '../api/types';
import axios, { AxiosError, AxiosResponse } from 'axios';

export default Vue.extend({
  components: { EmailInput },
  data() {
    return {
      loading: false,
      message: '',
      errors: { errors: {} } as FormErrors,

      email: '',
    };
  },
  methods: {
    async submit() {
      this.loading = true;

      await axios
        .post('/forgot-password', {
          email: this.email,
        })
        .then((response: AxiosResponse<ApiResponse>) => {
          if (response.data.message) {
            this.message = response.data.message;
          }
        })
        .catch((error: AxiosError<FormErrors>) => {
          if (error.response?.status === 429) {
            this.errors = {
              message: 'Please wait before retrying.',
              errors: {},
            };
          } else if (error.response?.data) {
            this.errors = error.response?.data;
          } else {
            this.message = 'Unknown error.';
          }
        });

      this.loading = false;
    },
  },
});
</script>
