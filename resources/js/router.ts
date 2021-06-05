import Vue from 'vue';
import VueRouter from 'vue-router';
import {
  ErrorPage404,
  HomePage,
  LoginPage,
  RegisterPage,
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
          path: '*',
          component: ErrorPage404,
        },
      ],
    },
  ],
});
