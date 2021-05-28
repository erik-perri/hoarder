export type GroupType = 'and' | 'or';

export interface CriteriaGroup {
  id?: string;
  group_type: GroupType;
  group_conditions: CriteriaConditions;
}

export interface CriteriaCondition {
  id?: string;
  match_field: string;
  match_comparison: string;
  match_value: string;
}

export type CriteriaConditions = Array<CriteriaGroup | CriteriaCondition>;

export function isCriteriaGroup(value: unknown): value is CriteriaGroup {
  return (value as CriteriaGroup).group_type !== undefined;
}

export function isCriteriaCondition(
  value: unknown
): value is CriteriaCondition {
  return (value as CriteriaCondition).match_field !== undefined;
}

// Ideally this would be in the component file but due to the way TS & Vue
// work together they would not be importable by other components.
export interface ConditionOptions {
  field: string;
  comparison: string;
  value: string;
}
