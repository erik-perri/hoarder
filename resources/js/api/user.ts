import { AxiosResponse } from 'axios';
import api from './api';
import { ApiResponse } from './types';

export interface User {
  name: string;
  email: string;
}

export async function loginUser(
  email: string,
  password: string,
  rememberMe: boolean
): Promise<ApiResponse<User>> {
  return await api
    .post('/login', {
      email,
      password,
      rememberMe,
    })
    .then((response: AxiosResponse<ApiResponse<User>>) => response.data)
    .catch((error: ApiResponse<User>) => error);
}

export async function logoutUser(): Promise<boolean> {
  return await api
    .post('/logout')
    .then(
      (response: AxiosResponse<ApiResponse>) =>
        response.data.status === 'success'
    )
    .catch(() => false);
}

export async function getLoggedInUser(): Promise<User | null> {
  return await api
    .get('/auth-status')
    .then((response: AxiosResponse<ApiResponse<User>>) => {
      if (response.data.status === 'success') {
        return response.data.data || null;
      }
      return null;
    })
    .catch(() => null);
}

export interface RegisterResponse {
  success: boolean;
  user?: User;
  redirect?: string;
  message?: string;
  errors?: Record<string, string[]>;
}

export interface RegisterApiResponse {
  user: User;
  redirect: string;
}

export async function registerUser(
  displayName: string,
  email: string,
  password: string,
  password_confirmation: string
): Promise<ApiResponse<RegisterApiResponse>> {
  return await api
    .post('/register', {
      name: displayName,
      email,
      password,
      password_confirmation,
    })
    .then(
      (response: AxiosResponse<ApiResponse<RegisterApiResponse>>) =>
        response.data
    )
    .catch((error: ApiResponse<RegisterApiResponse>) => error);
}

export interface ResetPasswordResponse {
  redirect: string;
}

export async function resetPassword(
  email: string,
  password: string,
  password_confirmation: string,
  token: string
): Promise<ApiResponse<ResetPasswordResponse>> {
  return await api
    .post('/reset-password', {
      email,
      password,
      password_confirmation,
      token,
    })
    .then((response: AxiosResponse<ApiResponse<ResetPasswordResponse>>) => {
      return response.data;
    })
    .catch((error: ApiResponse<ResetPasswordResponse>) => error);
}

export async function forgotPassword(email: string): Promise<ApiResponse> {
  return await api
    .post('/forgot-password', {
      email,
    })
    .then((response: AxiosResponse<ApiResponse>) => {
      return response.data;
    })
    .catch((error: ApiResponse) => error);
}

export async function sendEmailVerification(): Promise<ApiResponse> {
  return await api
    .post('/verify-email')
    .then((response: AxiosResponse<ApiResponse>) => {
      return response.data;
    })
    .catch((error: ApiResponse) => error);
}
