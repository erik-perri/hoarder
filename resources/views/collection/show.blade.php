<?php /* @var \App\Models\Collection $collection */ ?>
<?php /* @var \App\Models\Collection\Goal $goals [] */ ?>

@section('title', $collection->name)

<x-frontend-layout>
    <h1>{{ $collection->name }}</h1>

    <x-redirect-status />

    @auth
        <div>
            <a href="{{ route('collections.edit', ['collection' => $collection]) }}">Edit</a>
        </div>
    @endif

    <h2>Goals</h2>
    <div>
        <a href="{{ route('collections.goals.create', ['collection' => $collection]) }}">Create Goal</a>
        @if ($goals)
            <ul>
                @foreach($goals as $goal)
                    <li>
                        {{ $goal->name }}: {{ $progress[$goal->id]['percent'] ?? 0 }}%
                                         ({{ $progress[$goal->id]['stocked'] ?? 0 }}
                                         / {{ $progress[$goal->id]['total'] ?? 0 }})
                        <a href="{{ route('collections.goals.edit', ['collection' => $collection, 'goal' => $goal]) }}">Edit</a>
                        <a href="{{ route('collectibles.search', [
                            'collectible' => $collection->collectible,
                            'category_criteria' => json_encode($goal->category_criteria, JSON_THROW_ON_ERROR),
                            'item_criteria' => json_encode($goal->item_criteria, JSON_THROW_ON_ERROR),
                        ]) }}">Search</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <h2>Stock</h2>
    <?php
    $collectionStock = $collection->stock()->paginate(25);
    ?>
    <table>
        <tr>
            <th>Count</th>
            <th>Item</th>
            <th>Category</th>
            <th>Condition</th>
            <th>Language</th>
            <th>Tags</th>
        </tr>
        @foreach($collectionStock as $stock)
            <tr>
                <td>{{ $stock->count }}</td>
                <td>
                    <a href="{{ route('collectibles.categories.items.show', ['collectible' => $stock->item->collectible, 'category' => $stock->item->category, 'item' => $stock->item]) }}">
                        {{ $stock->item->name }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('collectibles.categories.show', ['collectible' => $stock->item->collectible, 'category' => $stock->item->category]) }}">
                        {{ $stock->item->category->name }}
                    </a>
                </td>
                <td>{{ $stock->condition }}</td>
                <td>{{ $stock->language }}</td>
                <td>{{ $stock->tags ? implode(', ', $stock->tags) : '' }}</td>
            </tr>
        @endforeach
    </table>

    <x-pagination :items="$collectionStock" />
</x-frontend-layout>
