import api from './api';
import { AxiosResponse } from 'axios';
import { ApiList, ApiResponse } from './types';

export interface Collectible {
  id: number;
  name: string;
  categoryFields: Array<CollectibleFieldModel>;
  itemFields: Array<CollectibleFieldModel>;
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
