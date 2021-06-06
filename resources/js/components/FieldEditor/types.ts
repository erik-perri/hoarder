import { CollectibleFieldModel } from '../../api/collectibles';

export type FieldEditorItem = Omit<CollectibleFieldModel, 'entity_type'>;
export type FieldEditorItems = Array<FieldEditorItem>;
