<?php

namespace App\Http\Requests\Collectible;

class CollectibleCreateRequest extends CollectibleEditRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                'name' => [
                    'required',
                    'unique:App\Models\Collectible,name',
                ],
            ],
        );
    }

    /**
     * Get the validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return array_merge(
            parent::messages(),
            [
                'name.required' => __('collectible.messages.name_unique'),
            ]
        );
    }
}
