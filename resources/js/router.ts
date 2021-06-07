import Vue from 'vue';
import VueRouter from 'vue-router';
import {
  CollectibleEditPage,
  CollectibleIndexPage,
  CollectibleShowPage,
  ErrorPage404,
  ForgotPasswordPage,
  HomePage,
  LoginPage,
  RegisterPage,
  ResetPasswordPage,
  VerifyEmailPage,
} from './pages';
import { BaseLayout, CollectibleLayout } from './layouts';

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
          name: 'home',
          component: HomePage,
        },
        {
          path: 'login',
          name: 'login',
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
          path: 'collectibles',
          component: CollectibleLayout,
          children: [
            {
              path: '',
              name: 'collectibles.index',
              component: CollectibleIndexPage,
            },
            {
              path: 'create',
              name: 'collectibles.create',
              component: CollectibleEditPage,
              meta: { requiresAuth: true },
            },
            {
              path: ':id',
              name: 'collectibles.show',
              component: CollectibleShowPage,
            },
            {
              path: ':id/edit',
              name: 'collectibles.edit',
              component: CollectibleEditPage,
              meta: { requiresAuth: true },
            },
          ],
        },
        {
          path: '*',
          component: ErrorPage404,
        },
      ],
    },
  ],
});
