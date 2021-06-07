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
          path: 'collectibles',
          component: CollectibleLayout,
          children: [
            {
              name: 'collectibles.index',
              path: '',
              component: CollectibleIndexPage,
            },
            {
              name: 'collectibles.create',
              path: 'create',
              component: CollectibleEditPage,
              meta: { requiresAuth: true },
            },
            {
              name: 'collectibles.show',
              path: ':id',
              component: CollectibleShowPage,
            },
            {
              name: 'collectibles.edit',
              path: ':id/edit',
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
