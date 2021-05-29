<?php

namespace App\Criteria;

use App\Criteria\Field\FieldInfoInterface;
use Illuminate\Database\Query\Builder;

class CriteriaApplier
{
    /**
     * @param FieldInfoInterface[] $fields
     */
    private array $fields;

    /**
     * @param FieldInfoInterface[] $fields
     */
    public function __construct(array $fields)
    {
        foreach ($fields as $field) {
            if (! ($field instanceof FieldInfoInterface)) {
                throw new \InvalidArgumentException('Invalid field supplied');
            }
        }

        $this->fields = $fields;
    }

    /**
     * @param Builder $builder The query builder to apply the criteria to
     * @param bool $groupIsOr Whether the base group is an 'or' (otherwise 'and' will be used)
     * @param array $criteria The criteria to apply
     */
    public function apply(Builder $builder, bool $groupIsOr, array $criteria): void
    {
        foreach ($criteria as $condition) {
            if (isset($condition['group_type'])) {
                $builder->{$groupIsOr ? 'orWhere' : 'where'}(function ($builder) use ($condition) {
                    $this->apply(
                        $builder,
                        $condition['group_type'] === 'or',
                        $condition['group_conditions']
                    );
                });
            } elseif (isset($condition['match_field'])) {
                $this->applyComparison(
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

    /**
     * @param Builder $builder
     * @param bool $or
     * @param string $identifier
     * @param string $comparison
     * @param string $value
     */
    private function applyComparison(
        Builder $builder,
        bool $or,
        string $identifier,
        string $comparison,
        string $value
    ): void {
        $info = $this->findFieldInfo($identifier);
        if (! $info) {
            throw new \InvalidArgumentException('Invalid condition, unknown field specified');
        }

        $info->getComparisonHandler()->apply(
            $builder,
            $or,
            $info->getColumn(),
            $comparison,
            $value
        );
    }

    /**
     * @param string $matchField
     * @return FieldInfoInterface|null
     */
    private function findFieldInfo(string $matchField): ?FieldInfoInterface
    {
        foreach ($this->fields as $field) {
            if ($field->getIdentifier() === $matchField) {
                return $field;
            }
        }

        return null;
    }
}
