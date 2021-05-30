import { CollectibleFieldModel } from '../../types';

export type FieldEditorItem = Omit<CollectibleFieldModel, 'entity_type'>;
export type FieldEditorItems = Array<FieldEditorItem>;
