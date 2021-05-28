<?php

namespace App\Criteria\Field;

use App\Criteria\Comparison\ComparisonInterface;

interface FieldInfoInterface
{
    public function getIdentifier(): string;

    public function getColumn(): string;

    public function getComparisonHandler(): ComparisonInterface;

    public function getColumnNameFormatter(): ?ColumnNameFormatterInterface;
}
