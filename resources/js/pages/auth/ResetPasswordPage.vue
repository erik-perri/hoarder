<template>
  <div>
    <h2>Reset Password</h2>

    <form method="post" id="reset-password">
      <div v-if="message">
        {{ message }}
      </div>
      <div v-if="errors.token">
        {{ errors.token.join(' ') }}
      </div>

      <EmailInput
        id="email"
        name="email"
        label="Email"
        v-model="email"
        :disabled="true"
        :errors="errors.email"
      />

      <PasswordInput
        id="password"
        name="password"
        label="Password"
        v-model="password"
        :errors="errors.password"
      />

      <PasswordInput
        id="password_confirmation"
        name="password_confirmation"
        label="Confirm password"
        v-model="password_confirmation"
        :errors="errors.password_confirmation"
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
import { EmailInput, PasswordInput } from '../../components/Forms';
import { resetPassword } from '../../api/user';

export default Vue.extend({
  data() {
    return {
      loading: false as boolean,
      message: undefined as string | undefined,
      errors: {} as Record<string, string[]>,
      email: this.$route.query.email as string,
      password: '' as string,
      password_confirmation: '' as string,
    };
  },
  methods: {
    async submit(): Promise<void> {
      this.message = undefined;
      this.errors = {};
      this.loading = true;

      const response = await resetPassword(
        this.email,
        this.password,
        this.password_confirmation,
        this.$route.params.token
      );

      if (response.status === 'success') {
        // TODO Figure out and add flash message
        await this.$router.push(response.data?.redirect || { name: 'home' });
      } else {
        this.message = response.message;
        this.errors = response.errors || {};
      }

      this.loading = false;
    },
  },
  components: { PasswordInput, EmailInput },
});
</script>
