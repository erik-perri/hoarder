<?php /* @var \App\Models\Collectible\Category $category */ ?>

@section('title', $category->id ? __('collectible.category.title.edit') : __('collectible.category.title.create'))

<x-frontend-layout>
    <h1>@yield('title')</h1>

    <x-redirect-status />
    <x-validation-errors :errors="$errors">{{ __('collectible.error_heading') }}</x-validation-errors>

    <form action="{{ $category->id
                        ? route('collectibles.categories.update', ['collectible' => $category->collectible, 'category' => $category])
                        : route('collectibles.categories.store', ['collectible' => $category->collectible]) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @if ($category->id)
            @method('PUT')
        @endif

        <x-forms.input-text name="name"
                            value="{{ old('name', $category->name) }}"
                            label="{{ __('collectible.category.label.name') }}"
                            required />

        <div class="category-fields">
            <x-collectible-fields name="field_values"
                                  :fields="$category->fields"
                                  :values="$category->field_values" />
        </div>

        <x-forms.button type="submit">
            {{ __('collectible.category.button.save') }}
        </x-forms.button>

        @if ($category->id)
            <a href="{{ route('collectibles.categories.show', ['collectible' => $category->collectible, 'category' => $category]) }}">
                {{ __('collectible.category.button.cancel') }}
            </a>
        @else
            <a href="{{ route('collectibles.categories.index', ['collectible' => $category->collectible]) }}">
                {{ __('collectible.category.button.cancel') }}
            </a>
        @endif
    </form>
</x-frontend-layout>
