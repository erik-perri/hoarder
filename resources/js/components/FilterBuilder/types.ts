export type GroupType = 'and' | 'or';

export interface FilterGroup {
  group_type: GroupType;
  group_conditions: FilterConditions;
}

export interface FilterCondition {
  match_field: string;
  match_comparison: string;
  match_value: string;
}

export type FilterConditions = Array<FilterGroup | FilterCondition>;

export function isFilterGroup(value: unknown): value is FilterGroup {
  return (value as FilterGroup).group_type !== undefined;
}

export function isFilterCondition(value: unknown): value is FilterCondition {
  return (value as FilterCondition).match_field !== undefined;
}

// Ideally this would be in the component file but due to the way TS & Vue
// work together they would not be importable by other components.
export interface ConditionOptions {
  field: string;
  comparison: string;
  value: string;
}
