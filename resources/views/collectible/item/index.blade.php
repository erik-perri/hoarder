<?php /* @var \App\Models\Collectible $collectible */ ?>
<?php /* @var \App\Models\Collectible\Category $category */ ?>
<?php /* @var \App\Models\Collectible\Item $item */ ?>

@section('title', sprintf('%s / %s / %s', $collectible->name, $category->name, $item->name))

<x-frontend-layout>
    <h1>{{ $item->name }}</h1>

    <table>
        <tbody>
        <tr>
            <td>Collectible</td>
            <td><a href="{{ route('collectibles.show', ['collectible' => $collectible]) }}">{{ $collectible->name }}</a>
            </td>
        </tr>
        <tr>
            <td>Category</td>
            <td><a href="{{ route('categories.show', ['category' => $category]) }}">{{ $category->name }}</a></td>
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
                                @if ($field->input_type === 'boolean')
                                    {{ $item->field_values[$field->code] ? 'Yes' : 'No' }}
                                @elseif ($field->input_type === 'textarea')
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
                    @elseif ($field->input_type === 'boolean')
                        unset
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</x-frontend-layout>
