<?php

namespace App\Http\Requests\Collection;

use App\Models\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CollectionEditRequest extends FormRequest
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
        /** @var Collection $collection */
        $collection = $this->route('collection');

        // If a Collection was passed to the route, it is an existing collection and we should ensure the name is unique
        // to its collectible, otherwise it is a new collection and we should check against the input collectible ID.
        $collectibleId = $collection->collectible->id ?? $this->get('collectible_id');

        return [
            'name' => [
                'required',
                Rule::unique(Collection::class, 'name')
                    ->where('user_id', $this->user()->id)
                    ->where('collectible_id', $collectibleId)
                    ->whereNot('id', $collection->id ?? null),
            ],
            'is_default' => [
                'sometimes',
                'accepted',
            ],
        ];
    }

    /**
     * Get the validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => __('collection.messages.name_required'),
            'name.unique' => __('collection.messages.name_unique'),
            'is_default.accepted' => __('collection.messages.is_default_accepted'),
        ];
    }
}
