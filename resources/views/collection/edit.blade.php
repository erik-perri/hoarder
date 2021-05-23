<?php /* @var \App\Models\Collection $collection */ ?>

@section('title', $collection->id ? __('collection.title.edit') : __('collection.title.create'))

<x-frontend-layout>
    <h1>{{ $collection->id ? __('collection.title.edit') : __('collection.title.create') }}</h1>

    <x-redirect-status />
    <x-validation-errors :errors="$errors">{{ __('collection.error_heading') }}</x-validation-errors>

    <form action="{{ $collection->id ? route('collections.update', ['collection' => $collection]) : route('collections.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @if ($collection->id)
            @method('PUT')
        @endif

        <x-forms.input-text name="name"
                            value="{{ old('name', $collection->name) }}"
                            label="{{ __('collection.label.name') }}"
                            required />

        @if ($collection->id)
            {{ __('collection.label.collectible') }}
            <a href="{{ route('collectibles.show', ['collectible' => $collection->collectible]) }}">
                {{ $collection->collectible->name }}
            </a>
        @else
            <x-forms.input-select label="{{ __('collection.label.collectible') }}"
                                  noSelectionText=""
                                  name="collectible_id"
                                  required
                                  value="{{ old('collectible_id', $collection->collectible_id) }}"
                                  :options="$collectibleOptions" />
        @endif

        <div>
            <x-forms.input-checkbox name="is_default"
                                    checked="{{ old('is_default', $collection->is_default) }}"
                                    label="{{ __('collection.label.is_default') }}" />
        </div>

        <x-forms.button type="submit">
            {{ __('collection.button.save') }}
        </x-forms.button>
    </form>

    @if ($collection->id)
        <form style="display: inline"
              method="POST"
              action="{{ route('collections.destroy', ['collection' => $collection]) }}">
            @csrf
            @method('DELETE')

            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('collection.button.delete') }}
            </a>
        </form>
    @endif
</x-frontend-layout>
