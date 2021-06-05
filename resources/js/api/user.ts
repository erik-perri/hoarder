import axios, { AxiosError, AxiosResponse } from 'axios';
import { ApiResponse, FormErrors } from './types';

export interface User {
  name: string;
  email: string;
}

export interface LoginSuccess {
  user: User;
}

export interface LoginFailure {
  errors: FormErrors;
}

export function isLoginSuccess(response: any): response is LoginSuccess {
  return !!(response as LoginSuccess).user;
}

export function isLoginFailure(response: any): response is LoginFailure {
  return !!(response as LoginFailure).errors;
}

export async function loginUser(
  email: string,
  password: string,
  rememberMe: boolean
): Promise<LoginSuccess | LoginFailure> {
  return await axios
    .post('/login', {
      email,
      password,
      rememberMe,
    })
    .then((response: AxiosResponse<ApiResponse<User>>) => {
      return { user: response.data.data } as LoginSuccess;
    })
    .catch((error: AxiosError<FormErrors>) => {
      const errors = error.response?.data;
      if (!errors) {
        throw error;
      }

      return { errors } as LoginFailure;
    });
}

export async function logoutUser(): Promise<ApiResponse> {
  return await axios
    .post('/logout')
    .then((response: AxiosResponse<ApiResponse>) => response.data);
}

export async function getLoggedInUser(): Promise<User | null> {
  return await axios
    .get('/auth-status')
    .then((response: AxiosResponse<ApiResponse<User>>) => {
      return response.data.data || null;
    })
    .catch((error: AxiosError) => {
      if (error.response?.status !== 401) {
        throw error;
      }

      return null;
    });
}
