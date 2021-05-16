<?php /* @var \App\Models\Collectible[] $collectibles */ ?>

@section('title', __('collectible.title.listing'))

<x-frontend-layout>
    <h1>{{ __('collectible.title.listing') }}</h1>

    <x-redirect-status />

    @auth
        <div>
            <a href="{{ route('collectibles.create') }}">Create Collectible</a>
        </div>
    @endif

    <ul>
    @foreach($collectibles as $item)
        <li><a href="{{ route('collectibles.show', ['collectible' => $item]) }}">{{ $item->name }}</a></li>
    @endforeach
    </ul>

    <x-pagination :items="$collectibles" />
</x-frontend-layout>
