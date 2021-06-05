import { User } from '../api/user';

export interface AuthState {
  currentUser?: User;
}

export interface State {
  auth: AuthState;
}
