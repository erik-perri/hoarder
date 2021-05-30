<?php

namespace App\Criteria\Comparison;

use Illuminate\Database\Query\Builder;

// TODO This either needs to be reworked to it can handle non-JSON fields as well or it needs to be renamed to be more
//      clear what it supports.
class Tag implements ComparisonInterface
{
    public const COMPARISON_CONTAINS_ANY = 'tag_contains_any';
    public const COMPARISON_CONTAINS_ONLY = 'tag_contains_only';
    public const COMPARISON_CONTAINS_ALL = 'tag_contains_all';

    public function apply(Builder $builder, bool $or, string $column, string $comparison, string $value): void
    {
        $builder->{$or ? 'orWhere' : 'where'}(function (Builder $builder) use (
            $column,
            $comparison,
            $value
        ) {
            $values = array_filter(array_map('trim', explode(',', $value)));

            switch ($comparison) {
                case static::COMPARISON_CONTAINS_ANY:
                    foreach ($values as $value) {
                        $builder->orWhereJsonContains(
                            $column,
                            $value
                        );
                    }
                    break;
                case static::COMPARISON_CONTAINS_ONLY:
                    $builder->whereJsonContains(
                        $column,
                        $values
                    );
                    $builder->whereJsonLength(
                        $column,
                        '=',
                        count($values)
                    );
                    break;
                case static::COMPARISON_CONTAINS_ALL:
                    $builder->whereJsonContains(
                        $column,
                        $values
                    );
                    break;
                default:
                    throw new \InvalidArgumentException('Invalid tag comparison');
            }
        });
    }
}
