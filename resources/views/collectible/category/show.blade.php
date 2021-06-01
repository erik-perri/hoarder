<?php /* @var \App\Models\Collectible $collectible */ ?>
<?php /* @var \App\Models\Collectible\Category $category */ ?>
<?php /* @var \App\Models\Collectible\Item[] $items */ ?>

@section('title', sprintf('%s / %s', $collectible->name, $category->name))

<x-frontend-layout>
    <h1>{{ $category->name }}</h1>

    <x-redirect-status />

    <div>
        @auth
            <a href="{{ route('collectibles.categories.edit', ['collectible' => $collectible, 'category' => $category]) }}">Edit Category</a>
            &nbsp;<a href="{{ route('collectibles.categories.items.create', ['collectible' => $collectible, 'category' => $category]) }}">Create Item</a>
        @endif
    </div>

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
                                @if ($field->input_type === \App\Collectible\Enum\FieldInputType::BOOLEAN)
                                    {{ $category->field_values[$field->code] ? 'Yes' : 'No' }}
                                @elseif ($field->input_type === \App\Collectible\Enum\FieldInputType::TEXTAREA)
                                    {!! nl2br(e($item->field_values[$field->code])) !!}
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
                    @elseif ($field->input_type === \App\Collectible\Enum\FieldInputType::BOOLEAN)
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
                <a href="{{ route('collectibles.categories.items.show', ['collectible' => $item->collectible, 'category' => $item->category, 'item' => $item]) }}">
                    {{ $item->name }}
                </a>
            </li>
        @endforeach
    </ul>

    <x-pagination :items="$items" />
</x-frontend-layout>
