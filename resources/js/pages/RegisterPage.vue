<template>
  <div>
    <h2>Register</h2>

    <form>
      <div v-if="errors.message">
        {{ errors.message }}
      </div>

      <TextInput
        id="name"
        name="name"
        label="Display name"
        v-model="name"
        :errors="errors.errors.name"
      />

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

      <PasswordInput
        id="passwordConfirmation"
        name="passwordConfirmation"
        label="Confirm password"
        v-model="passwordConfirmation"
        :errors="errors.errors.password_confirmation"
      />

      <div>
        <router-link to="/login">Already registered?</router-link>

        <button type="submit" :disabled="loading" @click.prevent="submit">
          Register
        </button>
      </div>
    </form>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { FormErrors } from '../api/types';
import {
  CheckboxInput,
  EmailInput,
  PasswordInput,
  TextInput,
} from '../components/Forms';
import { isAuthFailure, registerUser } from '../api/user';

export default Vue.extend({
  components: {
    CheckboxInput,
    EmailInput,
    PasswordInput,
    TextInput,
  },
  created() {
    if (this.$store.getters['auth/isLoggedIn']) {
      this.redirectToHome();
    }
  },
  data() {
    return {
      loading: false,
      errors: { errors: {} } as FormErrors,

      name: '',
      email: '',
      password: '',
      passwordConfirmation: '',
    };
  },
  methods: {
    redirectToHome() {
      this.$router.push('/');
    },
    async submit() {
      this.loading = true;

      const response = await registerUser(
        this.name,
        this.email,
        this.password,
        this.passwordConfirmation
      );

      if (isAuthFailure(response)) {
        this.errors = response.errors;
      } else {
        this.$store.commit('auth/login', response.user);
        await this.$router.push(response.redirect);
      }

      this.loading = false;
    },
  },
});
</script>
