import axios, { AxiosError, AxiosResponse } from 'axios';
import { ApiResponse, FormErrors } from './types';

export interface User {
  name: string;
  email: string;
}

export type UserApiFailure = FormErrors;

export function isAuthFailure(response: any): response is UserApiFailure {
  return !!(response as UserApiFailure).errors;
}

export interface LoginSuccess {
  user: User;
}

export async function loginUser(
  email: string,
  password: string,
  rememberMe: boolean
): Promise<LoginSuccess | UserApiFailure> {
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
      return error.response?.data as UserApiFailure;
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

export interface RegisterSuccess {
  user: User;
  redirect: string;
}

export async function registerUser(
  displayName: string,
  email: string,
  password: string,
  password_confirmation: string
): Promise<RegisterSuccess | UserApiFailure> {
  return await axios
    .post('/register', {
      name: displayName,
      email,
      password,
      password_confirmation,
    })
    .then((response: AxiosResponse<ApiResponse<RegisterSuccess>>) => {
      return response.data.data as RegisterSuccess;
    })
    .catch((error: AxiosError<FormErrors>) => {
      return error.response?.data as UserApiFailure;
    });
}
