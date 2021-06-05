<template>
  <div>
    <h2>Reset Password</h2>

    <form method="post" id="reset-password">
      <div v-if="errors.message">
        {{ errors.message }}
      </div>
      <div v-if="errors.errors.token">
        {{ errors.errors.token.join(' ') }}
      </div>

      <EmailInput
        id="email"
        name="email"
        label="Email"
        v-model="email"
        :disabled="true"
        :errors="errors.errors.email"
      />

      <PasswordInput
        id="password"
        name="password"
        label="Password"
        v-model="password"
        :errors="errors.errors.password"
      />

      <PasswordInput
        id="password_confirmation"
        name="password_confirmation"
        label="Confirm password"
        v-model="password_confirmation"
        :errors="errors.errors.password_confirmation"
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
import { EmailInput, PasswordInput } from '../components/Forms';
import { FormErrors } from '../api/types';
import { isAuthFailure, resetPassword } from '../api/user';

export default Vue.extend({
  components: { PasswordInput, EmailInput },
  data() {
    return {
      loading: false,
      message: '',
      errors: { errors: {} } as FormErrors,

      email: this.$route.query.email as string,
      password: '',
      password_confirmation: '',
    };
  },
  methods: {
    async submit() {
      this.loading = true;

      const response = await resetPassword(
        this.email,
        this.password,
        this.password_confirmation,
        this.$route.params.token
      );

      if (isAuthFailure(response)) {
        this.errors = response;
      } else {
        // TODO Figure out and add flash message
        await this.$router.push(response.redirect);
      }

      this.loading = false;
    },
  },
});
</script>
