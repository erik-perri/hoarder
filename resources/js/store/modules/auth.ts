import { ActionContext } from 'vuex';
import { AuthState, State as RootState } from '../state';
import { getLoggedInUser, loginUser, logoutUser, User } from '../../api/user';
import {
  clearLastLoginState,
  getLastLoginState,
  setLastLoginState,
} from '../../util/login';

export type AuthContext = ActionContext<AuthState, RootState>;

export interface LoginPayload {
  email: string;
  password: string;
  rememberMe: boolean;
}

function getLastUser(): User | undefined {
  // We load the last user from the local storage. When we load the page we send a background
  // request to confirm the authentication, this is just so we don't end up redirecting a user
  // due to the logged in state not being known yet.
  const state = getLastLoginState();
  if (state.maybeLoggedIn) {
    return {
      name: state.name || '',
      email: state.email || '',
    };
  }
  return undefined;
}

export default {
  namespaced: true,

  state: {
    currentUser: getLastUser(),
  },

  getters: {
    isLoggedIn(state: AuthState): boolean {
      return !!state.currentUser;
    },
  },

  mutations: {
    login(state: AuthState, user: User) {
      state.currentUser = user;
      setLastLoginState(user.name, user.email);
    },
    logout(state: AuthState) {
      state.currentUser = undefined;
      clearLastLoginState();
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
      } else {
        context.commit('logout');
      }
    },
  },
};
