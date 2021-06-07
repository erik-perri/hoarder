import api from './api';
import { AxiosResponse } from 'axios';
import { ApiList, ApiResponse } from './types';

export interface Collectible {
  id: number;
  name: string;
}

export interface CollectibleCategory {
  id: number;
  name: string;
  field_values: Record<string, unknown>;
}

export interface CollectibleItem {
  id: number;
  name: string;
  field_values: Record<string, unknown>;
}

export type CollectibleFieldInputType =
  | 'text'
  | 'date'
  | 'number'
  | 'boolean'
  | 'url'
  | 'textarea'
  | 'tags';
export type CollectibleFieldEntityType = 'category' | 'item';

export interface CollectibleFieldModel {
  // Hidden from serialization
  // id: number;
  // collectible_id: number;
  // created_at: string;
  // updated_at: string;
  entity_type: CollectibleFieldEntityType;
  code: string;
  name: string;
  input_type: CollectibleFieldInputType;
  input_options: Record<string, string>;
  is_required: boolean;
}

export async function getCollectibles(
  page: number = 1
): Promise<ApiResponse<ApiList<Collectible>>> {
  return await api
    .get(`/collectibles?page=${page}`)
    .then((response: AxiosResponse<ApiResponse<ApiList<Collectible>>>) => {
      return response.data;
    })
    .catch((error: ApiResponse) => error);
}

export interface GetCollectibleResponse {
  collectible: Collectible;
  categoryFields: Array<CollectibleFieldModel>;
  itemFields: Array<CollectibleFieldModel>;
}

export async function getCollectible(
  id: number
): Promise<ApiResponse<GetCollectibleResponse>> {
  return await api
    .get(`/collectibles/${id}`)
    .then((response: AxiosResponse<ApiResponse<GetCollectibleResponse>>) => {
      return response.data;
    })
    .catch((error: ApiResponse) => error);
}

export interface StoreCollectibleResponse {
  collectible: Collectible;
}

export async function storeOrUpdateCollectible(
  collectible: Collectible,
  categoryFields: Array<CollectibleFieldModel>,
  itemFields: Array<CollectibleFieldModel>
): Promise<ApiResponse<StoreCollectibleResponse>> {
  const data = {
    ...collectible,
    category_fields: categoryFields,
    item_fields: itemFields,
  };
  return await (collectible.id
    ? api.put(`/collectibles/${collectible.id}`, data)
    : api.post('/collectibles', data)
  )
    .then((response: AxiosResponse<ApiResponse<StoreCollectibleResponse>>) => {
      return response.data;
    })
    .catch((error: ApiResponse) => error);
}

export async function getCategories(
  collectibleId: number,
  page: number = 1
): Promise<ApiResponse<ApiList<CollectibleCategory>>> {
  return await api
    .get(`/collectibles/${collectibleId}/categories?page=${page}`)
    .then(
      (response: AxiosResponse<ApiResponse<ApiList<CollectibleCategory>>>) => {
        return response.data;
      }
    )
    .catch((error: ApiResponse) => error);
}

export interface GetCategoryResponse {
  category: CollectibleCategory;
}

export async function getCategory(
  collectibleId: number,
  categoryId: number
): Promise<ApiResponse<GetCategoryResponse>> {
  return await api
    .get(`/collectibles/${collectibleId}/categories/${categoryId}`)
    .then((response: AxiosResponse<ApiResponse<GetCategoryResponse>>) => {
      return response.data;
    })
    .catch((error: ApiResponse) => error);
}

export async function getItems(
  collectibleId: number,
  categoryId: number,
  page: number = 1
): Promise<ApiResponse<ApiList<CollectibleCategory>>> {
  return await api
    .get(
      `/collectibles/${collectibleId}/categories/${categoryId}/items?page=${page}`
    )
    .then(
      (response: AxiosResponse<ApiResponse<ApiList<CollectibleCategory>>>) => {
        return response.data;
      }
    )
    .catch((error: ApiResponse) => error);
}
