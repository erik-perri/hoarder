<?php

namespace App\Collectible;

use App\Collectible\Enum\FieldInputType;
use App\Criteria\Comparison\Boolean;
use App\Criteria\Comparison\ComparisonInterface;
use App\Criteria\Comparison\Date;
use App\Criteria\Comparison\Number;
use App\Criteria\Comparison\Tag;
use App\Criteria\Comparison\Text;
use App\Criteria\Field\FieldInfo;
use App\Criteria\Field\FieldInfoInterface;
use App\Criteria\Field\JsonColumnNameFormatter;
use App\Models\Collectible;

class FieldFactory
{
    /**
     * @param Collectible $collectible
     * @return FieldInfoInterface[]
     */
    public function createItemFields(Collectible $collectible): array
    {
        return $this->createFields($collectible, 'item');
    }

    /**
     * @param Collectible $collectible
     * @return FieldInfoInterface[]
     */
    public function createCategoryFields(Collectible $collectible): array
    {
        return $this->createFields($collectible, 'category');
    }

    /**
     * @param Collectible $collectible
     * @param string $entityType
     * @return FieldInfoInterface[]
     */
    private function createFields(Collectible $collectible, string $entityType): array
    {
        $fields = [
            new FieldInfo(
                'name',
                'Name',
                'ceddbc03-d875-4f1e-830f-22b6a01505d7',
                'text',
                new Text(),
                null
            ),
        ];

        $fieldValuesColumnFormatter = new JsonColumnNameFormatter('field_values');

        foreach ($collectible->fields as $field) {
            if ($field->entity_type !== $entityType) {
                continue;
            }

            $fields[] = new FieldInfo(
                $field->code,
                $field->name,
                $field->code,
                $field->input_type,
                $this->createComparisonHandler($field),
                $fieldValuesColumnFormatter
            );
        }

        return $fields;
    }

    /**
     * @param Collectible\Field $field
     * @return ComparisonInterface
     */
    private function createComparisonHandler(Collectible\Field $field): ComparisonInterface
    {
        switch ($field->input_type) {
            case FieldInputType::BOOLEAN:
                return new Boolean();
            case FieldInputType::DATE:
                return new Date();
            case FieldInputType::NUMBER:
                return new Number();
            case FieldInputType::TAGS:
                return new Tag();
            case FieldInputType::TEXT:
            case FieldInputType::TEXTAREA:
            case FieldInputType::URL:
                return new Text();
            default:
                throw new \InvalidArgumentException('Invalid input type');
        }
    }
}
