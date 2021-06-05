<template>
  <div>
    <h2>Login</h2>

    <form>
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

      <PasswordInput
        id="password"
        name="password"
        label="Password"
        v-model="password"
        :errors="errors.errors.password"
      />

      <CheckboxInput
        id="remember"
        name="remember"
        label="Remember me"
        v-model="remember"
        :errors="errors.errors.remember"
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
import { FormErrors } from '../api/types';
import { isAuthFailure, loginUser } from '../api/user';
import { CheckboxInput, EmailInput, PasswordInput } from '../components/Forms';

export default Vue.extend({
  components: { CheckboxInput, PasswordInput, EmailInput },
  created() {
    if (this.$store.getters['auth/isLoggedIn']) {
      this.redirectToHome();
    }
  },
  data() {
    return {
      loading: false,
      errors: { errors: {} } as FormErrors,

      email: '',
      password: '',
      remember: false,
    };
  },
  methods: {
    redirectToHome() {
      this.$router.push('/');
    },
    async submit() {
      this.loading = true;

      // TODO Should this be calling the store action instead?
      //      That is what we initially implemented but found the form error handling even more awkward than it is here.
      const response = await loginUser(
        this.email,
        this.password,
        this.remember
      );

      if (isAuthFailure(response)) {
        this.errors = response.errors;
      } else {
        this.$store.commit('auth/login', response.user);
        this.redirectToHome();
      }

      this.loading = false;
    },
  },
});
</script>
