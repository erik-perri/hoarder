export interface CollectibleField {
  display_name: string;
  identifier: string;
  input_type:
    | 'text'
    | 'date'
    | 'number'
    | 'boolean'
    | 'url'
    | 'textarea'
    | 'tags';
}
