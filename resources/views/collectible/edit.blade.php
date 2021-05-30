<?php /* @var \App\Models\Collectible $collectible */ ?>

@section('title', $collectible->id ? __('collectible.title.edit') : __('collectible.title.create'))

<x-frontend-layout>
    <h1>{{ $collectible->id ? __('collectible.title.edit') : __('collectible.title.create') }}</h1>

    <x-redirect-status />
    <x-validation-errors :errors="$errors">{{ __('collectible.error_heading') }}</x-validation-errors>

    <form action="{{ $collectible->id ? route('collectibles.update', ['collectible' => $collectible]) : route('collectibles.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @if ($collectible->id)
            @method('PUT')
        @endif

        <x-forms.input-text name="name"
                            value="{{ old('name', $collectible->name) }}"
                            label="{{ __('collectible.label.name') }}"
                            required />

        <h3>Category Fields</h3>
        <field-editor input-name="category_fields"
                      :items="{{ json_encode($categoryFields, JSON_THROW_ON_ERROR) }}">
        </field-editor>

        <h3>Item Fields</h3>
        <field-editor input-name="item_fields"
                      :items="{{ json_encode($itemFields, JSON_THROW_ON_ERROR) }}">
        </field-editor>

        <x-forms.button type="submit">
            {{ __('collectible.button.save') }}
        </x-forms.button>

    </form>
</x-frontend-layout>
