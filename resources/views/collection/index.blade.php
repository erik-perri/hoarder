<?php /* @var \App\Models\Collection[] $collections */ ?>

@section('title', __('collection.title.listing'))

<x-frontend-layout>
    <h1>{{ __('collection.title.listing') }}</h1>

    <x-redirect-status />

    @auth
        <div>
            <a href="{{ route('collections.create') }}">Create Collection</a>
        </div>
    @endif

    <ul>
        @foreach($collections as $item)
            <li>
                <a href="{{ route('collections.show', ['collection' => $item]) }}">{{ $item->name }}</a>
                @if ($item->is_default)
                    (Default for <a href="{{route('collectibles.show', ['collectible' => $item->collectible])}}">
                        {{ $item->collectible->name }}
                    </a>)
                @endif
            </li>
        @endforeach
    </ul>

    <x-pagination :items="$collections" />
</x-frontend-layout>
