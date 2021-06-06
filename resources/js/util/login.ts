import { getOnce, localStorage, sessionStorage } from './storage';
import { RawLocation } from 'vue-router';

export function storeLoginRedirect(path: string): void {
  sessionStorage.setItem('hoarder-login-redirect', path);
}

export function getLoginRedirect(defaultRoute = 'home'): string | RawLocation {
  return (
    getOnce(sessionStorage, 'hoarder-login-redirect') || { name: defaultRoute }
  );
}

export interface LoginState {
  maybeLoggedIn: boolean;
  name: string | null;
  email: string | null;
}

export function getLastLoginState(): LoginState {
  return {
    maybeLoggedIn: !!localStorage.getItem('hoarder-maybe-logged-in'),
    name: localStorage.getItem('hoarder-last-name'),
    email: localStorage.getItem('hoarder-last-email'),
  };
}

export function setLastLoginState(name: string, email: string): void {
  localStorage.setItem('hoarder-maybe-logged-in', '1');
  localStorage.setItem('hoarder-last-name', name);
  localStorage.setItem('hoarder-last-email', email);
}

export function clearLastLoginState(): void {
  localStorage.removeItem('hoarder-maybe-logged-in');
  localStorage.removeItem('hoarder-last-name');
}
