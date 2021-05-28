<?php

namespace App\Criteria\Field;

use App\Criteria\Comparison\ComparisonInterface;

class FieldInfo implements FieldInfoInterface, \JsonSerializable
{
    private string $column;
    private string $displayName;
    private string $identifier;
    private string $inputType;
    private ComparisonInterface $comparisonHandler;
    private ?ColumnNameFormatterInterface $columnNameFormatter;

    /**
     * @param string $column
     * @param string $displayName
     * @param string $identifier
     * @param string $inputType
     * @param ComparisonInterface $comparisonHandler
     * @param ColumnNameFormatterInterface|null $columnNameFormatter
     */
    public function __construct(
        string $column,
        string $displayName,
        string $identifier,
        string $inputType,
        ComparisonInterface $comparisonHandler,
        ?ColumnNameFormatterInterface $columnNameFormatter = null
    ) {
        $this->column = $column;
        $this->displayName = $displayName;
        $this->identifier = $identifier;
        $this->inputType = $inputType;
        $this->comparisonHandler = $comparisonHandler;
        $this->columnNameFormatter = $columnNameFormatter;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getInputType(): string
    {
        return $this->inputType;
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getComparisonHandler(): ComparisonInterface
    {
        return $this->comparisonHandler;
    }

    public function getColumnNameFormatter(): ?ColumnNameFormatterInterface
    {
        return $this->columnNameFormatter;
    }

    public function jsonSerialize(): array
    {
        return [
            'display_name' => $this->getDisplayName(),
            'identifier' => $this->getIdentifier(),
            'input_type' => $this->getInputType(),
        ];
    }
}
