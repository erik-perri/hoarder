<?php

namespace App\Criteria;

use Illuminate\Database\Query\Builder;

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
                $builder->{$groupIsOr ? 'orWhere' : 'where'}(function ($query) use ($condition) {
                    $this->apply(
                        $query,
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
            case 'equals':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, '=', $matchValue);
                break;
            case 'does not equal':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, '!=', $matchValue);
                break;

            case 'contains':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, 'LIKE', '%'.$matchValue.'%');
                break;
            case 'does not contain':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, 'NOT LIKE', '%'.$matchValue.'%');
                break;

            case 'starts with':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, 'LIKE', $matchValue.'%');
                break;
            case 'does not start with':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, 'NOT LIKE', $matchValue.'%');
                break;

            case 'ends with':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, 'LIKE', '%'.$matchValue);
                break;
            case 'does not end with':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, 'NOT LIKE', '%'.$matchValue);
                break;

            case 'tags_contains':
                $builder->{$groupIsOr ? 'orWhereJsonContains' : 'whereJsonContains'}(
                    'field_values->'.$matchField,
                    $matchValue
                );
                break;

            case 'bool':
                switch ($matchValue) {
                    case 'true':
                        $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, '=', true);
                        break;
                    case 'false':
                        $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, '=', false);
                        break;
                    case 'unset':
                        $builder->{$groupIsOr ? 'orWhereNull' : 'whereNull'}('field_values->'.$matchField);
                        break;
                }
                break;

            default:
                throw new \InvalidArgumentException('Invalid condition, invalid match_comparison supplied');
        }
    }
}
