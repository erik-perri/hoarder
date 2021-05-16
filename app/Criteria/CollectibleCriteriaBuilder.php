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
            } elseif (isset($condition['match_type'])) {
                $this->applyCondition(
                    $builder,
                    $groupIsOr,
                    $condition['match_type'],
                    $condition['match_field'],
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
        string $matchType,
        string $matchField,
        string $matchValue
    ): void {
        if (! in_array($matchField, $this->allowedFields, true)) {
            throw new \InvalidArgumentException('Invalid condition, unknown field specified');
        }

        switch ($matchType) {
            case 'exact':
                $builder->{$groupIsOr ? 'orWhere' : 'where'}('field_values->'.$matchField, '=', $matchValue);
                break;

            case 'contains':
                $builder->{$groupIsOr ? 'orWhereJsonContains' : 'whereJsonContains'}(
                    'field_values->'.$matchField,
                    $matchValue
                );
                break;

            default:
                throw new \InvalidArgumentException('Invalid condition, invalid match_type supplied');
        }
    }
}
