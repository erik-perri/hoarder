import { CollectibleFieldInputType } from './api/collectibles';

export interface CollectibleField {
  display_name: string;
  identifier: string;
  input_type: CollectibleFieldInputType;
  is_required: boolean;
}
