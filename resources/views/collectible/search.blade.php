<?php /* @var \App\Models\Collectible $collectible */ ?>
<?php /* @var \App\Models\Collectible\Category[] $categories */ ?>

@section('title', sprintf('Search %s', $collectible->name))

<x-frontend-layout>
    <h1>{{ sprintf('Search %s', $collectible->name) }}</h1>

    <x-redirect-status />

    <form method="get" action="{{ route('collectibles.search', ['collectible' => $collectible]) }}">
        @csrf

        <strong>Category filter</strong>
        <filter-builder input-name="categoryFilter"
                        :conditions="{{ json_encode($categoryFilter, JSON_THROW_ON_ERROR) }}"
                        :fields="{{ json_encode($categoryFields, JSON_THROW_ON_ERROR) }}"></filter-builder>

        <strong>Item filter</strong>
        <filter-builder input-name="itemFilter"
                        :conditions="{{ json_encode($itemFilter, JSON_THROW_ON_ERROR) }}"
                        :fields="{{ json_encode($itemFields, JSON_THROW_ON_ERROR) }}"></filter-builder>
        <button type="submit">Search</button>
    </form>

    @if ($results && count($results))
        <?php $appends = [
            'categoryFilter' => request()->get('categoryFilter'),
            'itemFilter' => request()->get('itemFilter'),
        ]; ?>
        <x-pagination :appends="$appends" :items="$results" />

        <ul>
            @foreach($results as $item)
                <li>
                    <a href="{{ route('items.show', ['item' => $item->id]) }}">
                        {{ $item->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</x-frontend-layout>
