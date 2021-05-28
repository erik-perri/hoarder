<?php

namespace App\Criteria\Comparison;

use Illuminate\Database\Query\Builder;

interface ComparisonInterface
{
    public function apply(Builder $builder, bool $or, string $column, string $comparison, string $value): void;
}
