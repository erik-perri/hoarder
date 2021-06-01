<?php

namespace App\Collectible;

use App\Collectible\Enum\FieldInputType;
use App\Models\Collectible\Field;
use Illuminate\Database\Eloquent\Collection;

class FieldValueProcessor
{
    /**
     * @param Field[]|Collection $fields
     * @param array $inputValues
     * @return array
     */
    public function getFieldValues(Collection $fields, array $inputValues): array
    {
        $processedValues = [];

        // TODO This needs to be handled better, we need to choose a tag editor so we know how it will be formatted when
        //      we receive it.  We also have plans on having tags be either limited or not, which will need to be
        //      validated here, or ItemEditRequest will need to be adjusted.
        $tagFields = $fields->where('input_type', '=', FieldInputType::TAGS);
        foreach ($inputValues as $fieldCode => $fieldValue) {
            if ($fieldValue === null) {
                continue;
            }

            if ($tagFields->contains('code', $fieldCode)) {
                $fieldValue = array_map('trim', explode(',', $fieldValue));
            }

            $processedValues[$fieldCode] = $fieldValue;
        }

        return $processedValues;
    }
}
