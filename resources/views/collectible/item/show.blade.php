<?php /* @var \App\Models\Collectible $collectible */ ?>
<?php /* @var \App\Models\Collectible\Category $category */ ?>
<?php /* @var \App\Models\Collectible\Item $item */ ?>

@section('title', sprintf('%s / %s / %s', $collectible->name, $category->name, $item->name))

<x-frontend-layout>
    <h1>{{ $item->name }}</h1>

    <x-redirect-status />

    <div>
        @auth
            <a href="{{ route('collectibles.categories.items.edit', ['collectible' => $collectible, 'category' => $category, 'item' => $item]) }}">
                Edit Item
            </a>
        @endif
    </div>

    <table>
        <tbody>
        <tr>
            <td>Collectible</td>
            <td><a href="{{ route('collectibles.show', ['collectible' => $collectible]) }}">{{ $collectible->name }}</a>
            </td>
        </tr>
        <tr>
            <td>Category</td>
            <td>
                <a href="{{ route('collectibles.categories.show', ['collectible' => $collectible, 'category' => $category]) }}">
                    {{ $category->name }}
                </a>
            </td>
        </tr>
        @foreach($collectible->fields->where('entity_type', 'item') as $field)
            <tr>
                <td>{{ $field->name}}</td>
                <td>
                    @if (isset($item->field_values[$field->code]))
                        <div>
                            @if (is_array($item->field_values[$field->code]))
                                {{ implode(', ', $item->field_values[$field->code]) }}
                            @else
                                @if ($field->input_type === \App\Collectible\Enum\FieldInputType::BOOLEAN)
                                    {{ $item->field_values[$field->code] ? 'Yes' : 'No' }}
                                @elseif ($field->input_type === \App\Collectible\Enum\FieldInputType::TEXTAREA)
                                    {!! nl2br(e($item->field_values[$field->code])) !!}
                                @else
                                    {{ $item->field_values[$field->code] }}
                                @endif
                            @endif
                        </div>

                        @if ($field->code === 'image_url')
                            <a target="_blank" href="{{ $item->field_values[$field->code] }}">
                                <img style="max-height: 150px"
                                     src="{{ $item->field_values[$field->code] }}"
                                     alt="Item image" />
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
</x-frontend-layout>
