<?php /* @var \App\Models\Collectible\Item $item */ ?>

@section('title', $item->id ? __('collectible.item.title.edit') : __('collectible.item.title.create'))

<x-frontend-layout>
    <h1>@yield('title')</h1>

    <x-redirect-status />
    <x-validation-errors :errors="$errors">{{ __('collectible.error_heading') }}</x-validation-errors>

    <form action="{{ $item->id
                        ? route('collectibles.categories.items.update', ['collectible' => $item->collectible, 'category' => $item->category, 'item' => $item])
                        : route('collectibles.categories.items.store', ['collectible' => $item->collectible, 'category' => $item->category]) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @if ($item->id)
            @method('PUT')
        @endif

        <x-forms.input-text name="name"
                            value="{{ old('name', $item->name) }}"
                            label="{{ __('collectible.item.label.name') }}"
                            required />

        <div class="item-fields">
            <x-collectible-fields name="field_values"
                                  :fields="$item->fields"
                                  :values="$item->field_values" />
        </div>

        <x-forms.button type="submit">
            {{ __('collectible.item.button.save') }}
        </x-forms.button>

        @if ($item->id)
            <a href="{{ route('collectibles.categories.items.show', ['collectible' => $item->collectible, 'category' => $item->category, 'item' => $item]) }}">
                {{ __('collectible.item.button.cancel') }}
            </a>
        @else
            <a href="{{ route('collectibles.categories.items.index', ['collectible' => $item->collectible, 'category' => $item->category]) }}">
                {{ __('collectible.item.button.cancel') }}
            </a>
        @endif
    </form>
</x-frontend-layout>
