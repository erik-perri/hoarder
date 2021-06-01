<?php

namespace App\Criteria\Comparison;

use Illuminate\Database\Query\Builder;

class Date implements ComparisonInterface
{
    public const COMPARISON_ON = 'date_on';
    public const COMPARISON_ON_OR_AFTER = 'date_on_or_after';
    public const COMPARISON_AFTER = 'date_after';
    public const COMPARISON_ON_OR_BEFORE = 'date_on_or_before';
    public const COMPARISON_BEFORE = 'date_before';

    public function apply(Builder $builder, bool $or, string $column, string $comparison, string $value): void
    {
        switch ($comparison) {
            case static::COMPARISON_ON:
                $operator = '=';
                break;
            case static::COMPARISON_ON_OR_AFTER:
                $operator = '>=';
                break;
            case static::COMPARISON_AFTER:
                $operator = '>';
                break;
            case static::COMPARISON_ON_OR_BEFORE:
                $operator = '<=';
                break;
            case static::COMPARISON_BEFORE:
                $operator = '<';
                break;
            default:
                throw new \InvalidArgumentException('Invalid date comparison');
        }

        if ($or) {
            $builder->orWhereDate($column, $operator, $value);
        } else {
            $builder->whereDate($column, $operator, $value);
        }
    }
}
