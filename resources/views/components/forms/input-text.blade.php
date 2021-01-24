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

<div>
    {!! $slot !!}
    @if ($label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endif
    <input {{ $attributes->merge(['type' => 'text']) }}
           id="{{ $id }}"
           name="{{ $name }}"
           value="{{ $value }}" />
</div>
