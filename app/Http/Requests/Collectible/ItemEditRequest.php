<?php

namespace App\Http\Requests\Collectible;

use App\Collectible\FieldValidatorBuilder;
use App\Models\Collectible;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ItemEditRequest extends FormRequest
{
    /**
     * @var Collectible\Field[]
     */
    private array $fields;

    protected function prepareForValidation(): void
    {
        /** @var Collectible $collectible */
        $collectible = $this->route('collectible');

        $this->fields = $collectible->fields->where('entity_type', '=', 'item')->all();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $ruleGroups[] = [
            'name' => [
                'required',
                'unique:App\Models\Collectible\Item,name,'.($this->route('item')->id ?? 0),
            ],
        ];

        $validatorBuilder = new FieldValidatorBuilder();
        foreach ($this->fields as $field) {
            $fieldValidators = $validatorBuilder->getValidators($field);
            if (count($fieldValidators)) {
                $ruleGroups[] = ['field_values.'.$field->code => $fieldValidators];
            }
        }

        return array_merge(...$ruleGroups);
    }

    /**
     * Get the validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        // TODO Figure out how to handle field_values messages
        return [
            'name.required' => __('collectible.item.messages.name_required'),
            'name.unique' => __('collectible.item.messages.name_unique'),
        ];
    }
}
