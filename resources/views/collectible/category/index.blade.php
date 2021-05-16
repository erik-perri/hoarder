<?php /* @var \App\Models\Collectible $collectible */ ?>
<?php /* @var \App\Models\Collectible\Category $category */ ?>
<?php /* @var \App\Models\Collectible\Item[] $items */ ?>

@section('title', sprintf('%s / %s', $collectible->name, $category->name))

<x-frontend-layout>
    <h1>{{ $category->name }}</h1>

    <ul>
        @foreach($items as $item)
            <li>
                <a href="{{ route('items.show', ['item' => $item]) }}">
                    {{ $item->name }}
                </a>
            </li>
        @endforeach
    </ul>

    <x-pagination :items="$items" />
</x-frontend-layout>
