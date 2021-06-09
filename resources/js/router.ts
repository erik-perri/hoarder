import Vue from 'vue';
import VueRouter from 'vue-router';
import {
  CategoryShowPage,
  CollectibleEditPage,
  CollectibleIndexPage,
  CollectibleSearchPage,
  CollectibleShowPage,
  CollectionIndexPage,
  CollectionShowPage,
  ErrorPage404,
  ForgotPasswordPage,
  HomePage,
  ItemShowPage,
  LoginPage,
  RegisterPage,
  ResetPasswordPage,
  VerifyEmailPage,
} from './pages';
import {
  BaseLayout,
  CollectibleInjectorLayout,
  CollectibleCategoryInjectorLayout,
  CollectibleItemInjectorLayout,
  CollectionInjectorLayout,
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
          name: 'register',
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
              path: 'search',
              name: 'collectibles.search',
              component: CollectibleSearchPage,
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
                {
                  path: 'items/:item',
                  component: CollectibleItemInjectorLayout,
                  children: [
                    {
                      path: '',
                      name: 'items.show',
                      component: ItemShowPage,
                    },
                  ],
                },
              ],
            },
          ],
        },
        {
          path: 'collections',
          name: 'collections.index',
          component: CollectionIndexPage,
        },
        {
          path: 'collections/:collection',
          component: CollectionInjectorLayout,
          children: [
            {
              path: '',
              name: 'collections.show',
              component: CollectionShowPage,
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
