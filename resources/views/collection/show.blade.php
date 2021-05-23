<?php /* @var \App\Models\Collection $collection */ ?>

@section('title', $collection->name)

<x-frontend-layout>
    <h1>{{ $collection->name }}</h1>

    <x-redirect-status />

    <div>
        @auth
            <a href="{{ route('collections.edit', ['collection' => $collection]) }}">Edit</a>
        @endif
    </div>

    <?php
    $collectionStock = $collection->stock()->paginate(25);
    ?>
    <table>
        <tr>
            <th>Count</th>
            <th>Item</th>
            <th>Condition</th>
            <th>Language</th>
            <th>Tags</th>
        </tr>
        @foreach($collectionStock as $stock)
            <tr>
                <td>{{ $stock->count }}</td>
                <td><a href="{{ route('items.show', ['item' => $stock->item]) }}">{{ $stock->item->name }}</a></td>
                <td>{{ $stock->condition }}</td>
                <td>{{ $stock->language }}</td>
                <td>{{ $stock->tags ? implode(', ', $stock->tags) : '' }}</td>
            </tr>
        @endforeach
    </table>

    <x-pagination :items="$collectionStock" />
</x-frontend-layout>
