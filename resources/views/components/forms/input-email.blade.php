@props(['attributes', 'name', 'value', 'id' => null, 'label' => null])

<?php
/** @var \Illuminate\View\ComponentAttributeBag $attributes */
/** @var string $name */
/** @var string $value */
/** @var ?string $id */
/** @var ?string $label */
/** @var ?string $slot */

if (!$id) {
    $id = \Str::slug($name).'-'.\Str::random(8);
}
?>

<x-forms.input-text {{ $attributes->merge(['type' => 'email']) }}
                    name="{{ $name }}"
                    value="{{ $value }}"
                    id="{{ $id }}"
                    label="{{ $label }}">
    {!! $slot !!}
</x-forms.input-text>
