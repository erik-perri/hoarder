<?php

namespace App\Criteria\Comparison;

use Illuminate\Database\Query\Builder;

class Number implements ComparisonInterface
{
    public const COMPARISON_EQUALS = 'number_equals';
    public const COMPARISON_DOES_NOT_EQUAL = 'number_does_not_equal';
    public const COMPARISON_GREATER_THAN = 'number_greater_than';
    public const COMPARISON_GREATER_THAN_OR_EQUAL = 'number_greater_than_or_equal';
    public const COMPARISON_LESS_THAN = 'number_less_than';
    public const COMPARISON_LESS_THAN_OR_EQUAL = 'number_less_than_or_equal';

    public function apply(Builder $builder, bool $or, string $column, string $comparison, string $value): void
    {
        switch ($comparison) {
            case static::COMPARISON_EQUALS:
                $operator = '=';
                break;
            case static::COMPARISON_DOES_NOT_EQUAL:
                $operator = '!=';
                break;
            case static::COMPARISON_GREATER_THAN:
                $operator = '>';
                break;
            case static::COMPARISON_GREATER_THAN_OR_EQUAL:
                $operator = '>=';
                break;
            case static::COMPARISON_LESS_THAN:
                $operator = '<';
                break;
            case static::COMPARISON_LESS_THAN_OR_EQUAL:
                $operator = '<=';
                break;
            default:
                throw new \InvalidArgumentException('Invalid number comparison');
        }

        if ($or) {
            $builder->orWhere($column, $operator, $value);
        } else {
            $builder->where($column, $operator, $value);
        }
    }
}
