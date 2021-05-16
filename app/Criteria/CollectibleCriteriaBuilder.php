<?php

namespace App\Criteria;

use Illuminate\Database\Query\Builder;

class CollectibleCriteriaBuilder
{
    /**
     * @param Builder $builder The query builder to apply the criteria to
     * @param bool $groupIsOr Whether the base group is an 'or' (otherwise 'and' will be used)
     * @param array $conditions The conditions to apply
     */
    public function apply(Builder $builder, bool $groupIsOr, array $conditions): void
    {
        foreach ($conditions as $condition) {
            if (! isset($condition['match_type'])) {
                throw new \InvalidArgumentException('Invalid condition, no match_type supplied');
            }

            if ($condition['match_type'] === 'group') {
                $builder->{$groupIsOr ? 'orWhere' : 'where'}(function ($query) use ($condition) {
                    $this->apply(
                        $query,
                        $condition['group_type'] === 'or',
                        $condition['conditions']
                    );
                });
            } else {
                $this->applyCondition(
                    $builder,
                    $groupIsOr,
                    $condition['match_type'],
                    $condition['match_field'],
                    $condition['match_value']
                );
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
