<?php

namespace App\Criteria\Comparison;

use Illuminate\Database\Query\Builder;

class Boolean implements ComparisonInterface
{
    public const COMPARISON_IS = 'boolean_is';
    public const COMPARISON_IS_NOT = 'boolean_is_not';

    public const VALUE_TRUE = 'true';
    public const VALUE_FALSE = 'false';
    public const VALUE_UNSET = 'unset';

    public function apply(Builder $builder, bool $or, string $column, string $comparison, string $value): void
    {
        switch ($value) {
            case static::VALUE_TRUE:
            case static::VALUE_FALSE:
                $boolValue = ($value === static::VALUE_TRUE);

                if ($comparison === static::COMPARISON_IS_NOT) {
                    $boolValue = ! $boolValue;
                }

                if ($or) {
                    $builder->orWhere($column, '=', $boolValue);
                } else {
                    $builder->where($column, '=', $boolValue);
                }
                break;
            case static::VALUE_UNSET:
                if ($comparison === static::COMPARISON_IS_NOT) {
                    if ($or) {
                        $builder->orWhereNotNull($column);
                    } else {
                        $builder->whereNotNull($column);
                    }
                } else {
                    if ($or) {
                        $builder->orWhereNull($column);
                    } else {
                        $builder->whereNull($column);
                    }
                }
                break;
            default:
                throw new \InvalidArgumentException('Invalid boolean value');
        }
    }
}
