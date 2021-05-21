export interface CollectibleField {
  uuid: string;
  name: string;
  code: string;
  input_type:
    | 'text'
    | 'date'
    | 'integer'
    | 'boolean'
    | 'url'
    | 'textarea'
    | 'tags';
}
