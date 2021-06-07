import { Collectible, CollectibleFieldModel } from '../../api/collectibles';

export { default as CollectibleForm } from './CollectibleForm.vue';

export interface CollectibleFormErrors {
  collectible?: Record<string, string[]>;
  categoryFields?: Record<string, string[]>;
  itemFields?: Record<string, string[]>;
}

export type CollectibleFormSubmitHandlerReturn = void | CollectibleFormErrors;

export interface CollectibleFormSubmitHandler {
  (
    collectible: Collectible,
    categoryFields: Array<CollectibleFieldModel>,
    itemFields: Array<CollectibleFieldModel>
  ): CollectibleFormSubmitHandlerReturn;
}
