<?php

namespace App\Http\Requests\Collection;

use App\Models\Collectible;
use Illuminate\Validation\Rule;

class CollectionCreateRequest extends CollectionEditRequest
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
                'collectible_id' => [
                    'required',
                    Rule::exists(Collectible::class, 'id'),
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
                'collectible_id.required' => __('collection.messages.collectible_id_required'),
                'collectible_id.exists' => __('collection.messages.collectible_id_exists'),
            ]
        );
    }
}
