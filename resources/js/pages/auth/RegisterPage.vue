<template>
  <div>
    <h2>Register</h2>

    <form method="post" id="register-form">
      <div v-if="message">
        {{ message }}
      </div>

      <TextInput
        id="name"
        name="name"
        label="Display name"
        v-model="name"
        :errors="errors.name"
      />

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

      <PasswordInput
        id="password_confirmation"
        name="password_confirmation"
        label="Confirm password"
        v-model="password_confirmation"
        :errors="errors.password_confirmation"
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
import {
  CheckboxInput,
  EmailInput,
  PasswordInput,
  TextInput,
} from '../../components/Forms';
import { registerUser } from '../../api/user';
import { getLoginRedirect } from '../../util/login';

export default Vue.extend({
  data() {
    return {
      loading: false as boolean,
      message: undefined as string | undefined,
      errors: {} as Record<string, string[]>,
      name: '' as string,
      email: '' as string,
      password: '' as string,
      password_confirmation: '' as string,
    };
  },
  methods: {
    async submit(): Promise<void> {
      this.message = undefined;
      this.errors = {};
      this.loading = true;

      const response = await registerUser(
        this.name,
        this.email,
        this.password,
        this.password_confirmation
      );

      if (response.status === 'success') {
        this.$store.commit('auth/login', response.data?.user);
        await this.$router.push(response.data?.redirect || getLoginRedirect());
      } else {
        this.message = response.message;
        this.errors = response.errors || {};
      }

      this.loading = false;
    },
  },
  components: {
    CheckboxInput,
    EmailInput,
    PasswordInput,
    TextInput,
  },
});
</script>
