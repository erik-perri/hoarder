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
          name: 'home',
          path: '',
          component: HomePage,
        },
        {
          name: 'login',
          path: 'login',
          component: LoginPage,
          meta: { requiresAuth: false },
        },
        {
          path: 'register',
          component: RegisterPage,
          meta: { requiresAuth: false },
        },
        {
          path: 'verify-email',
          component: VerifyEmailPage,
          meta: { requiresAuth: true },
        },
        {
          path: 'forgot-password',
          component: ForgotPasswordPage,
          meta: { requiresAuth: false },
        },
        {
          path: 'reset-password/:token',
          component: ResetPasswordPage,
          meta: { requiresAuth: false },
        },
        {
          path: '*',
          component: ErrorPage404,
        },
      ],
    },
  ],
});
