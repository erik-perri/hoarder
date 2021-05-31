<?php

namespace App\Collectible;

use App\Collectible\Enum\FieldInputType;
use App\Models\Collectible\Field;

class FieldValidatorBuilder
{
    /**
     * @param Field $field
     * @return string[]
     */
    public function getValidators(Field $field): array
    {
        $fieldValidators = $field->is_required ? ['required'] : ['nullable'];

        switch ($field->input_type) {
            case FieldInputType::TEXT:
            case FieldInputType::TEXTAREA:
            case FieldInputType::TAGS:
                $fieldValidators[] = 'string';
                break;

            case FieldInputType::URL:
                $fieldValidators[] = 'url';
                break;

            case FieldInputType::BOOLEAN:
                $fieldValidators[] = 'boolean';
                break;

            case FieldInputType::NUMBER:
                $fieldValidators[] = 'numeric';
                break;

            case FieldInputType::DATE:
                $fieldValidators[] = 'date';
                break;

            default:
                throw new \InvalidArgumentException('Unhandled input type '.$field->input_type);
        }

        return $fieldValidators;
    }
}
