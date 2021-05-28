<?php

namespace App\Criteria\Field;

interface ColumnNameFormatterInterface
{
    public function formatName(string $column): string;
}
