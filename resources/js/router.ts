import Vue from 'vue';
import VueRouter from 'vue-router';
import {
  ErrorPage404,
  ForgotPasswordPage,
  HomePage,
  LoginPage,
  RegisterPage,
  ResetPasswordPage,
  VerifyEmailPage,
} from './pages';
import { BaseLayout } from './layouts';

Vue.use(VueRouter);

export default new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      component: BaseLayout,
      children: [
        {
          path: '',
          component: HomePage,
        },
        {
          path: 'login',
          component: LoginPage,
        },
        {
          path: 'register',
          component: RegisterPage,
        },
        {
          path: 'verify-email',
          component: VerifyEmailPage,
        },
        {
          path: 'forgot-password',
          component: ForgotPasswordPage,
        },
        {
          path: 'reset-password/:token',
          component: ResetPasswordPage,
        },
        {
          path: '*',
          component: ErrorPage404,
        },
      ],
    },
  ],
});
