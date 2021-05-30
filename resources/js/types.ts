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

export interface CollectibleField {
  display_name: string;
  identifier: string;
  input_type: CollectibleFieldInputType;
  is_required: boolean;
}
