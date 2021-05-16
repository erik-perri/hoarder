<?php /* @var \App\Models\Collectible $collectible */ ?>
<?php /* @var \App\Models\Collectible\Category $category */ ?>
<?php /* @var \App\Models\Collectible\Item $item */ ?>

@section('title', sprintf('%s / %s / %s', $collectible->name, $category->name, $item->name))

<x-frontend-layout>
    <h1>{{ $item->name }}</h1>

    <table>
        <tbody>
        @foreach($collectible->fields->where('entity_type', 'item') as $field)
            <tr>
                <td>{{ $field->name}}</td>
                <td>
                    @if (isset($item->field_values[$field->code]))
                        @if (is_array($item->field_values[$field->code]))
                            {{ implode(', ', $item->field_values[$field->code]) }}
                        @else
                            {{ $item->field_values[$field->code] }}
                        @endif

                        @if ($field->code === 'image-url')
                            <div><img src="{{ $item->field_values[$field->code] }}" alt="Item image" /></div>
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</x-frontend-layout>
