import { getOnce, sessionStorage } from './storage';
import { RawLocation } from 'vue-router';

export function storeLoginRedirect(path: string): void {
  sessionStorage.setItem('hoarder-login-redirect', path);
}

export function getLoginRedirect(defaultRoute = 'home'): string | RawLocation {
  return (
    getOnce(sessionStorage, 'hoarder-login-redirect') || { name: defaultRoute }
  );
}
