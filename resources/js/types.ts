export interface CollectibleField {
  display_name: string;
  identifier: string;
  input_type:
    | 'text'
    | 'date'
    | 'integer'
    | 'boolean'
    | 'url'
    | 'textarea'
    | 'tags';
}
