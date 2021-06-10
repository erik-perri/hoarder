import api from './api';
import { AxiosResponse } from 'axios';
import { ApiList, ApiResponse } from './types';
import { CriteriaConditions } from '../components/CriteriaBuilder';
import { CollectibleCategory, CollectibleItem } from './collectibles';

export interface Collection {
  id: number;
  collectible_id: number;
  name: string;
  is_default: boolean;
}

export interface Goal {
  id: number;
  collection_id: number;
  name: string;
  category_criteria: CriteriaConditions;
  item_criteria: CriteriaConditions;
  stock_criteria: CriteriaConditions;
}

export interface GoalProgress {
  total: number;
  stocked: number;
  progress: number;
}

export interface Stock {
  id: number;
  collection_id: number;
  item_id: number;
  count: number;
  condition: string;
  language: string;
  tags: Array<string>;
}

export async function getCollections(
  page: number = 1
): Promise<ApiResponse<ApiList<Collection>>> {
  return await api
    .get(`/collections?page=${page}`)
    .then((response: AxiosResponse<ApiResponse<ApiList<Collection>>>) => {
      return response.data;
    })
    .catch((error: ApiResponse) => error);
}

export interface GetCollectionResponse {
  collection: Collection;
}

export async function getCollection(
  collectionId: number
): Promise<ApiResponse<GetCollectionResponse>> {
  return await api
    .get(`/collections/${collectionId}`)
    .then((response: AxiosResponse<ApiResponse<GetCollectionResponse>>) => {
      return response.data;
    })
    .catch((error: ApiResponse) => error);
}

export interface GetGoalsResponse {
  goal: Goal;
  progress: GoalProgress;
}

export async function getGoals(
  collectionId: number,
  page: number = 1
): Promise<ApiResponse<ApiList<GetGoalsResponse>>> {
  return await api
    .get(`/collections/${collectionId}/goals?page=${page}`)
    .then((response: AxiosResponse<ApiResponse<ApiList<GetGoalsResponse>>>) => {
      return response.data;
    })
    .catch((error: ApiResponse) => error);
}

export interface StockRelated {
  item: CollectibleItem;
  category: CollectibleCategory;
}

export async function getStock(
  collectionId: number,
  page: number = 1
): Promise<ApiResponse<ApiList<Stock, StockRelated>>> {
  return await api
    .get(`/collections/${collectionId}/stock?page=${page}`)
    .then(
      (response: AxiosResponse<ApiResponse<ApiList<Stock, StockRelated>>>) => {
        return response.data;
      }
    )
    .catch((error: ApiResponse) => error);
}
