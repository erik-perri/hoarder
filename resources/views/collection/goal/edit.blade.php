<?php /* @var \App\Models\Collection\Goal $goal */ ?>

@section('title', $goal->id ? __('collection.goal.title.edit') : __('collection.goal.title.create'))

<x-frontend-layout>
    <h1>{{ $goal->id ? __('collection.goal.title.edit') : __('collection.goal.title.create') }}</h1>

    <x-redirect-status />
    <x-validation-errors :errors="$errors">{{ __('collection.error_heading') }}</x-validation-errors>

    <form action="{{ $goal->id
                        ? route('collections.goals.update', ['collection' => $goal->collection, 'goal' => $goal])
                        : route('collections.goals.store', ['collection' => $goal->collection]) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @if ($goal->id)
            @method('PUT')
        @endif

        <x-forms.input-text name="name"
                            value="{{ old('name', $goal->name) }}"
                            label="{{ __('collection.goal.label.name') }}"
                            required />

        <strong>Category criteria</strong>
        <criteria-builder input-name="category_criteria"
                          :conditions="{{ old('category_criteria', json_encode($categoryCriteria, JSON_THROW_ON_ERROR)) }}"
                          :fields="{{ json_encode($categoryFields, JSON_THROW_ON_ERROR) }}">
        </criteria-builder>

        <strong>Item criteria</strong>
        <criteria-builder input-name="item_criteria"
                          :conditions="{{ old('item_criteria', json_encode($itemCriteria, JSON_THROW_ON_ERROR)) }}"
                          :fields="{{ json_encode($itemFields, JSON_THROW_ON_ERROR) }}">
        </criteria-builder>

        <strong>Stock criteria</strong>
        <criteria-builder input-name="stock_criteria"
                          :conditions="{{ old('stock_criteria', json_encode($stockCriteria, JSON_THROW_ON_ERROR)) }}"
                          :fields="{{ json_encode($stockFields, JSON_THROW_ON_ERROR) }}">
        </criteria-builder>

        <x-forms.button type="submit">
            {{ __('collection.goal.button.save') }}
        </x-forms.button>
    </form>

    @if ($goal->id)
        <form style="display: inline"
              method="POST"
              action="{{ route('collections.goals.destroy', ['collection' => $goal->collection, 'goal' => $goal]) }}">
            @csrf
            @method('DELETE')

            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('collection.goal.button.delete') }}
            </a>
        </form>
    @endif
</x-frontend-layout>
