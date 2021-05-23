@props(['attributes', 'name', 'value', 'options', 'id' => null, 'label' => null, 'noSelectionText' => null])

<?php
/** @var \Illuminate\View\ComponentAttributeBag $attributes */
/* @var string $name */
/* @var string $value */
/* @var array $options */
/** @var ?string $id */
/* @var ?string $label */
/* @var ?string $noSelectionText */
/* @var ?string $slot */

if (! $id) {
    $id = \Str::slug($name).'-'.\Str::random(8);
}
?>

<div>
    {!! $slot !!}
    @if ($label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endif

    <select {{ $attributes }}
            id="{{ $id }}"
            name="{{ $name }}">
        @if ($noSelectionText !== null)
            <option value="" {{ !$value ? 'selected' : '' }}>
                {{ $noSelectionText }}
            </option>
        @endif
        @foreach ($options as $optionValue => $optionText)
            <option value="{{ $optionValue }}" {{ $value === (string)$optionValue ? 'selected' : '' }}>
                {{ $optionText }}
            </option>
        @endforeach
    </select>
</div>
