<?php /* @var \App\Models\Collectible $collectible */ ?>
<?php /* @var \App\Models\Collectible\Category $category */ ?>
<?php /* @var \App\Models\Collectible\Item[] $items */ ?>

@section('title', sprintf('%s / %s', $collectible->name, $category->name))

<x-frontend-layout>
    <h1>{{ $category->name }}</h1>

    <table>
        <tbody>
        <tr>
            <td>Collectible</td>
            <td><a href="{{ route('collectibles.show', ['collectible' => $collectible]) }}">{{ $collectible->name }}</a>
            </td>
        </tr>
        @foreach($collectible->fields->where('entity_type', 'category') as $field)
            <tr>
                <td>{{ $field->name}}</td>
                <td>
                    @if (isset($category->field_values[$field->code]))
                        <div>
                            @if (is_array($category->field_values[$field->code]))
                                {{ implode(', ', $category->field_values[$field->code]) }}
                            @else
                                @if ($field->input_type === 'boolean')
                                    {{ $category->field_values[$field->code] ? 'Yes' : 'No' }}
                                @else
                                    {{ $category->field_values[$field->code] }}
                                @endif
                            @endif
                        </div>

                        @if ($field->code === 'logo_url')
                            <a target="_blank" href="{{ $category->field_values[$field->code] }}">
                                <img style="max-height: 150px"
                                     src="{{ $category->field_values[$field->code] }}"
                                     alt="Category image" />
                            </a>
                        @endif
                    @elseif ($field->input_type === 'boolean')
                        unset
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h2>Items</h2>
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
