<?php

namespace App\Criteria\Field;

class JsonColumnNameFormatter implements ColumnNameFormatterInterface
{
    private string $baseColumn;

    /**
     * @param string $baseColumn
     */
    public function __construct(string $baseColumn)
    {
        $this->baseColumn = $baseColumn;
    }

    /**
     * @param string $column
     * @return string
     */
    public function formatName(string $column): string
    {
        return sprintf('%s->%s', $this->baseColumn, $column);
    }
}
