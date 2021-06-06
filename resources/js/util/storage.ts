import { storageFactory } from 'storage-factory';

export const localStorage = storageFactory(() => window.localStorage);
export const sessionStorage = storageFactory(() => window.sessionStorage);

export function getOnce(storage: Storage, key: string): string | null {
  const value = storage.getItem(key);

  storage.removeItem(key);

  return value;
}
