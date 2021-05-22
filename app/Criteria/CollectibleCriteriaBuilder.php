<?php

namespace App\Criteria;

use Illuminate\Database\Query\Builder;

// TODO Rename this to better describe what it does
// TODO Refactor this to use an injected list of matcher instead of the unmanageable switch
class CollectibleCriteriaBuilder
{
    private array $allowedFields;

    public function __construct(array $allowedFields)
    {
        $this->allowedFields = $allowedFields;
    }

    /**
     * @param Builder $builder The query builder to apply the criteria to
     * @param bool $groupIsOr Whether the base group is an 'or' (otherwise 'and' will be used)
     * @param array $conditions The conditions to apply
     */
    public function apply(Builder $builder, bool $groupIsOr, array $conditions): void
    {
        foreach ($conditions as $condition) {
            if (isset($condition['group_type'])) {
                $builder->{$groupIsOr ? 'orWhere' : 'where'}(function ($builder) use ($condition) {
                    $this->apply(
                        $builder,
                        $condition['group_type'] === 'or',
                        $condition['group_conditions']
                    );
                });
            } elseif (isset($condition['match_field'])) {
                $this->applyCondition(
                    $builder,
                    $groupIsOr,
                    $condition['match_field'],
                    $condition['match_comparison'],
                    $condition['match_value']
                );
            } else {
                throw new \InvalidArgumentException('Invalid condition, unknown type');
            }
        }
    }

    private function applyCondition(
        Builder $builder,
        bool $groupIsOr,
        string $matchField,
        string $matchComparison,
        string $matchValue
    ): void {
        if (! in_array($matchField, $this->allowedFields, true)) {
            throw new \InvalidArgumentException('Invalid condition, unknown field specified');
        }

        switch ($matchComparison) {
            case 'boolean_is':
            case 'boolean_is_not':
                switch ($matchValue) {
                    case 'true':
                        $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, '=',
                            ($matchComparison === 'boolean_is'));
                        break;
                    case 'false':
                        $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, '=',
                            ! ($matchComparison === 'boolean_is'));
                        break;
                    case 'unset':
                        if ($matchComparison === 'boolean_is') {
                            $builder->{$groupIsOr ? 'orWhereNull' : 'whereNull'}('field_values->'.$matchField);
                        } else {
                            $builder->{$groupIsOr ? 'orWhereNotNull' : 'whereNotNull'}('field_values->'.$matchField);
                        }
                        break;
                }
                break;

            case 'date_on':
                $builder->{$groupIsOr ? 'orWhereDate' : 'whereDate'}('field_values->'.$matchField, '=', $matchValue);
                break;
            case 'date_before':
                $builder->{$groupIsOr ? 'orWhereDate' : 'whereDate'}('field_values->'.$matchField, '<', $matchValue);
                break;
            case 'date_after':
                $builder->{$groupIsOr ? 'orWhereDate' : 'whereDate'}('field_values->'.$matchField, '>', $matchValue);
                break;
            case 'date_on_or_before':
                $builder->{$groupIsOr ? 'orWhereDate' : 'whereDate'}('field_values->'.$matchField, '<=', $matchValue);
                break;
            case 'date_on_or_after':
                $builder->{$groupIsOr ? 'orWhereDate' : 'whereDate'}('field_values->'.$matchField, '>=', $matchValue);
                break;

            case 'text_equals':
            case 'number_equals':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, '=', $matchValue);
                break;
            case 'number_greater_than':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, '>', $matchValue);
                break;
            case 'number_greater_than_or_equal':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, '>=', $matchValue);
                break;
            case 'number_less_than':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, '<', $matchValue);
                break;
            case 'number_less_than_or_equal':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, '<=', $matchValue);
                break;

            case 'tag_contains_any':
            case 'tag_contains_only':
            case 'tag_contains_all':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}(function (Builder $builder) use (
                    $matchField,
                    $matchComparison,
                    $matchValue
                ) {
                    $matchValues = array_filter(array_map('trim', explode(',', $matchValue)));

                    switch ($matchComparison) {
                        case 'tag_contains_any':
                            foreach ($matchValues as $value) {
                                $builder->orWhereJsonContains(
                                    'field_values->'.$matchField,
                                    $value
                                );
                            }
                            break;
                        case 'tag_contains_only':
                            $builder->whereJsonContains(
                                'field_values->'.$matchField,
                                $matchValues
                            );
                            $builder->whereJsonLength(
                                'field_values->'.$matchField,
                                '=',
                                count($matchValues)
                            );
                            break;
                        case 'tag_contains_all':
                            $builder->whereJsonContains(
                                'field_values->'.$matchField,
                                $matchValues
                            );
                            break;
                    }
                });
                break;

//            case 'text_equals':
//                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, '=', $matchValue);
//                break;
            case 'text_contains':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, 'LIKE', '%'.$matchValue.'%');
                break;
            case 'text_starts_with':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, 'LIKE', $matchValue.'%');
                break;
            case 'text_ends_with':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, 'LIKE', '%'.$matchValue);
                break;
            case 'text_does_not_equal':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, '!=', $matchValue);
                break;
            case 'text_does_not_contain':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, 'NOT LIKE',
                    '%'.$matchValue.'%');
                break;
            case 'text_does_not_start_with':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, 'NOT LIKE', $matchValue.'%');
                break;
            case 'text_does_not_end_with':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, 'NOT LIKE', '%'.$matchValue);
                break;

            default:
                throw new \InvalidArgumentException('Invalid condition, invalid match_comparison supplied "'.$matchComparison.'"');
        }
    }
}
