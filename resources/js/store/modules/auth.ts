import { ActionContext } from 'vuex';
import { AuthState, State as RootState } from '../state';
import { getLoggedInUser, loginUser, logoutUser, User } from '../../api/user';

export type AuthContext = ActionContext<AuthState, RootState>;

export interface LoginPayload {
  email: string;
  password: string;
  rememberMe: boolean;
}

export default {
  namespaced: true,

  state: {
    currentUser: undefined,
  },

  getters: {
    isLoggedIn(state: AuthState): boolean {
      return !!state.currentUser;
    },
  },

  mutations: {
    login(state: AuthState, user: User) {
      state.currentUser = user;
    },
    logout(state: AuthState) {
      state.currentUser = undefined;
    },
  },

  actions: {
    async tryLogin(context: AuthContext, payload: LoginPayload) {
      const loginResponse = await loginUser(
        payload.email,
        payload.password,
        payload.rememberMe
      );

      if (loginResponse.status === 'success') {
        context.commit('logout');
      } else {
        context.commit('login', loginResponse.data);
      }
    },
    async tryLogout(context: AuthContext) {
      const response = await logoutUser();
      if (response) {
        context.commit('logout');
      }
    },
    async checkAuth(context: AuthContext) {
      const currentUser = await getLoggedInUser();
      if (currentUser) {
        context.commit('login', currentUser);
      }
    },
  },
};
