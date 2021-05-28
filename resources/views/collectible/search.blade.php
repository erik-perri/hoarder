<?php /* @var \App\Models\Collectible $collectible */ ?>
<?php /* @var \App\Models\Collectible\Category[] $categories */ ?>

@section('title', sprintf('Search %s', $collectible->name))

<x-frontend-layout>
    <h1>{{ sprintf('Search %s', $collectible->name) }}</h1>

    <x-redirect-status />

    <form method="get" action="{{ route('collectibles.search', ['collectible' => $collectible]) }}">
        @csrf

        <strong>Category criteria</strong>
        <criteria-builder input-name="category_criteria"
                          :conditions="{{ json_encode($categoryCriteria, JSON_THROW_ON_ERROR) }}"
                          :fields="{{ json_encode($categoryFields, JSON_THROW_ON_ERROR) }}"></criteria-builder>

        <strong>Item criteria</strong>
        <criteria-builder input-name="item_criteria"
                          :conditions="{{ json_encode($itemCriteria, JSON_THROW_ON_ERROR) }}"
                          :fields="{{ json_encode($itemFields, JSON_THROW_ON_ERROR) }}"></criteria-builder>
        <button type="submit">Search</button>
    </form>

    @if ($results && count($results))
        <?php $appends = [
            'category_criteria' => request()->get('category_criteria'),
            'item_criteria' => request()->get('item_criteria'),
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
