import {
  CollectibleFieldInputType,
  CollectibleFieldModel,
} from '../../api/collectibles';

export interface FieldEditorItem
  extends Omit<CollectibleFieldModel, 'entity_type'> {
  is_new: boolean;
  is_removed: boolean;
}

export type FieldEditorItems = Array<FieldEditorItem>;

export interface FieldInputUpdate {
  fieldName: string;
  inputType: CollectibleFieldInputType;
  isRequired: boolean;
}
