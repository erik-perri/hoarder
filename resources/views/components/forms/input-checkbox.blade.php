@props(['name', 'checked' => false, 'value' => null, 'id' => null, 'label' => null])

<?php
/** @var string $name */
/** @var ?string $value */
/** @var boolean $checked */
/** @var ?string $id */
/** @var ?string $label */

if (!$id) {
    $id = \Str::slug($name).'-'.\Str::random(8);
}
?>

<label for={{ $id }}>
    <input {{ $checked ? 'checked' : '' }}
           {{ $value ? 'value="'.$value.'"' : '' }}
           type="checkbox"
           id="{{ $id }}"
           name="{{ $name }}" />
    @if ($label)
        {{ $label }}
    @endif
</label>
