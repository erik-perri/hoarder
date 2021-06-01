@props(['attributes', 'fields', 'name', 'values'])

<?php
/** @var \Illuminate\View\ComponentAttributeBag $attributes */
/* @var \App\Models\Collectible\Field[] $fields */
/* @var string $name */
/* @var string[] $values */
?>

<div {{ $attributes }}>
    @foreach($fields as $field)
        @switch($field->input_type)
            @case(\App\Collectible\Enum\FieldInputType::TAGS)
            <x-forms.input-text name="{{ $name }}[{{ $field->code }}]"
                                value="{{ old($name.'['.$field->code.']', implode(', ', $values[$field->code] ?? [])) }}"
                                label="{{ $field->name }}" />
            @break

            @case(\App\Collectible\Enum\FieldInputType::TEXT)
            @case(\App\Collectible\Enum\FieldInputType::URL)
            <x-forms.input-text name="{{ $name }}[{{ $field->code }}]"
                                value="{{ old($name.'['.$field->code.']', $values[$field->code] ?? '') }}"
                                label="{{ $field->name }}" />
            @break

            @case(\App\Collectible\Enum\FieldInputType::TEXTAREA)
            <x-forms.input-textarea name="{{ $name }}[{{ $field->code }}]"
                                    value="{{ old($name.'['.$field->code.']', $values[$field->code] ?? '') }}"
                                    label="{{ $field->name }}" />
            @break

            @case(\App\Collectible\Enum\FieldInputType::DATE)
            <x-forms.input-text type="date"
                                pattern="\d{4}-\d{2}-\d{2}"
                                name="{{ $name }}[{{ $field->code }}]"
                                value="{{ old($name.'['.$field->code.']', $values[$field->code] ?? '') }}"
                                label="{{ $field->name }}" />
            @break

            @case(\App\Collectible\Enum\FieldInputType::NUMBER)
            <x-forms.input-text type="number"
                                name="{{ $name }}[{{ $field->code }}]"
                                value="{{ old($name.'['.$field->code.']', $values[$field->code] ?? '') }}"
                                label="{{ $field->name }}" />
            @break

            @case(\App\Collectible\Enum\FieldInputType::BOOLEAN)
            <?php
            // TODO Do we really want an unset option? What is the actual use case?
            $value = '';
            if ($values && array_key_exists($field->code, $values)) {
                $value = $values[$field->code] ? '1' : '0';
            }
            $options = ['' => 'Unset', '1' => 'Yes', '0' => 'No'];
            ?>
            <x-forms.input-select name="{{ $name }}[{{ $field->code }}]"
                                  value="{{ old($name.'['.$field->code.']', $value) }}"
                                  :options="$options"
                                  label="{{ $field->name }}" />
            @break

            @default
            {{$field}}
            @break
        @endswitch
    @endforeach
</div>
