<?php /* @var \App\Models\Collectible $collectible */ ?>
<?php /* @var \App\Models\Collectible\Category[] $categories */ ?>

@section('title', $collectible->name)

<x-frontend-layout>
    <h1>{{ $collectible->name }}</h1>

    <ul>
    @foreach($categories as $category)
        <li><a href="{{ route('collectibles.items', ['collectible' => $collectible, 'category' => $category]) }}">{{ $category->name }}</a></li>
    @endforeach
    </ul>

    <x-pagination :items="$categories" />
</x-frontend-layout>
