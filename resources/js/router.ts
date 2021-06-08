import Vue from 'vue';
import VueRouter from 'vue-router';
import {
  CategoryShowPage,
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
import {
  BaseLayout,
  CollectibleInjectorLayout,
  CollectibleCategoryInjectorLayout,
} from './layouts';

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
          name: 'collectibles.index',
          component: CollectibleIndexPage,
        },
        {
          path: 'collectibles/create',
          name: 'collectibles.create',
          component: CollectibleEditPage,
          meta: { requiresAuth: true },
        },
        {
          path: 'collectibles/:collectible',
          component: CollectibleInjectorLayout,
          children: [
            {
              path: '',
              name: 'collectibles.show',
              component: CollectibleShowPage,
            },
            {
              path: 'edit',
              name: 'collectibles.edit',
              component: CollectibleEditPage,
              meta: { requiresAuth: true },
            },
            {
              path: 'categories/:category',
              component: CollectibleCategoryInjectorLayout,
              children: [
                {
                  path: '',
                  name: 'categories.show',
                  component: CategoryShowPage,
                },
              ],
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
