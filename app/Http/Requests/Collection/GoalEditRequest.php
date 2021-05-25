<?php

namespace App\Http\Requests\Collection;

use App\Models\Collection\Goal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GoalEditRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $collection = $this->route('collection');
        $goal = $this->route('goal');

        return [
            'name' => [
                'required',
                Rule::unique(Goal::class, 'name')
                    ->where('collection_id', $collection->id)
                    ->whereNot('id', $goal->id ?? null),
            ],
            'category_criteria' => [
                'json',
            ],
            'item_criteria' => [
                'json',
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
            'name.required' => __('collection.goal.messages.name_required'),
            'name.unique' => __('collection.goal.messages.name_unique'),
            'category_criteria.json' => __('collection.goal.messages.category_criteria_json'),
            'item_criteria.json' => __('collection.goal.messages.item_criteria_json'),
        ];
    }
}
