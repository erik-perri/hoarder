<?php

namespace App\Criteria\Comparison;

use Illuminate\Database\Query\Builder;

class Text implements ComparisonInterface
{
    public const COMPARISON_EQUALS = 'text_equals';
    public const COMPARISON_DOES_NOT_EQUAL = 'text_does_not_equal';
    public const COMPARISON_CONTAINS = 'text_contains';
    public const COMPARISON_DOES_NOT_CONTAIN = 'text_does_not_contain';
    public const COMPARISON_STARTS_WITH = 'text_starts_with';
    public const COMPARISON_DOES_NOT_START_WITH = 'text_does_not_start_with';
    public const COMPARISON_ENDS_WITH = 'text_ends_with';
    public const COMPARISON_DOES_NOT_END_WITH = 'text_does_not_end_with';

    public function apply(Builder $builder, bool $or, string $column, string $comparison, string $value): void
    {
        switch ($comparison) {
            case static::COMPARISON_EQUALS:
                $operator = '=';
                break;
            case static::COMPARISON_DOES_NOT_EQUAL:
                $operator = '!=';
                break;
            case static::COMPARISON_CONTAINS:
            case static::COMPARISON_DOES_NOT_CONTAIN:
                $operator = $comparison === static::COMPARISON_CONTAINS
                    ? 'LIKE'
                    : 'NOT LIKE';
                $value = '%'.$value.'%';
                break;
            case static::COMPARISON_STARTS_WITH:
            case static::COMPARISON_DOES_NOT_START_WITH:
                $operator = $comparison === static::COMPARISON_STARTS_WITH
                    ? 'LIKE'
                    : 'NOT LIKE';
                $value .= '%';
                break;
            case static::COMPARISON_ENDS_WITH:
            case static::COMPARISON_DOES_NOT_END_WITH:
                $operator = $comparison === static::COMPARISON_ENDS_WITH
                    ? 'LIKE'
                    : 'NOT LIKE';
                $value = '%'.$value;
                break;
            default:
                throw new \InvalidArgumentException('Invalid text comparison');
        }

        if ($or) {
            $builder->orWhere($column, $operator, $value);
        } else {
            $builder->where($column, $operator, $value);
        }
    }
}
