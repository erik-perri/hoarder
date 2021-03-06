<template>
  <div>
    <h2>Login</h2>

    <form method="post" id="login-form">
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

      <PasswordInput
        id="password"
        name="password"
        label="Password"
        v-model="password"
        :errors="errors.password"
      />

      <CheckboxInput
        id="remember"
        name="remember"
        label="Remember me"
        v-model="remember"
        :errors="errors.remember"
      />

      <div>
        <router-link to="/forgot-password">Forgot your password?</router-link>

        <button type="submit" :disabled="loading" @click.prevent="submit">
          Login
        </button>
      </div>
    </form>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { loginUser } from '../../api/user';
import {
  CheckboxInput,
  EmailInput,
  PasswordInput,
} from '../../components/Forms';
import { getLastLoginState, getLoginRedirect } from '../../util/login';

export default Vue.extend({
  data() {
    return {
      loading: false as boolean,
      message: undefined as string | undefined,
      errors: {} as Record<string, string[]>,
      email: (getLastLoginState().email || '') as string,
      password: '' as string,
      remember: false as boolean,
    };
  },
  methods: {
    async submit(): Promise<void> {
      this.message = undefined;
      this.errors = {};
      this.loading = true;

      // TODO Should this be calling the store action instead?
      //      That is what we initially implemented but found the form error handling even more awkward than it is here.
      const response = await loginUser(
        this.email,
        this.password,
        this.remember
      );

      if (response.status === 'success') {
        this.$store.commit('auth/login', response.data);
        await this.$router.push(getLoginRedirect());
      } else {
        this.message = response.message;
        this.errors = response.errors || {};
      }

      this.loading = false;
    },
  },
  components: { CheckboxInput, PasswordInput, EmailInput },
});
</script>
