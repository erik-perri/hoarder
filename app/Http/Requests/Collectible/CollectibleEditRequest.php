<?php

namespace App\Http\Requests\Collectible;

use App\Collectible\Enum\FieldInputType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CollectibleEditRequest extends FormRequest
{
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
                'unique:App\Models\Collectible,name,'.($this->route('collectible')->id ?? 0),
            ],
        ];

        foreach (['category_fields', 'item_fields'] as $fieldKey) {
            $ruleGroups[] = [
                $fieldKey => 'array',
                $fieldKey.'.*.name' => [
                    'required_unless:'.$fieldKey.'.*.is_removed,1',
                    'string',
                ],
                $fieldKey.'.*.input_type' => [
                    'required_unless:'.$fieldKey.'.*.is_removed,1',
                    Rule::in(FieldInputType::getAvailableTypes()),
                ],
                $fieldKey.'.*.is_required' => [
                    'boolean',
                ],
                $fieldKey.'.*.is_removed' => [
                    'boolean',
                ],
            ];
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
        return [
            'name.required' => __('collectible.messages.name_required'),
            'name.unique' => __('collectible.messages.name_unique'),
        ];
    }
}
