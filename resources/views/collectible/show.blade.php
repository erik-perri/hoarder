<?php /* @var \App\Models\Collectible $collectible */ ?>
<?php /* @var \App\Models\Collectible\Category[] $categories */ ?>

@section('title', $collectible->name)

<x-frontend-layout>
    <h1>{{ $collectible->name }}</h1>

    <x-redirect-status />

    <div>
        <a href="{{ route('collectibles.search', ['collectible' => $collectible]) }}">Search</a>
    @auth
        <a href="{{ route('collectibles.edit', ['collectible' => $collectible]) }}">Edit</a>
    @endif
    </div>

    <ul>
    @foreach($categories as $category)
        <li><a href="{{ route('categories.show', ['category' => $category]) }}">{{ $category->name }}</a></li>
    @endforeach
    </ul>

    <x-pagination :items="$categories" />
</x-frontend-layout>
