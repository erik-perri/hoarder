<?php /* @var \App\Models\Collectible $collectible */ ?>
<?php /* @var \App\Models\Collectible\Category[] $categories */ ?>

@section('title', $collectible->name)

<x-frontend-layout>
    <h1>{{ $collectible->name }}</h1>

    <x-redirect-status />

    @auth
        <div>
            <a href="{{ route('collectibles.edit', ['collectible' => $collectible]) }}">Edit</a>
        </div>
    @endif

    <ul>
    @foreach($categories as $category)
        <li><a href="{{ route('categories.show', ['category' => $category]) }}">{{ $category->name }}</a></li>
    @endforeach
    </ul>

    <x-pagination :items="$categories" />
</x-frontend-layout>
