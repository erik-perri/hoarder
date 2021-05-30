<?php

namespace App\Collectible\Enum;

// TODO Investigate using an enum library, or whether we instead want individual field input classes for each which
//      could contain logic and the identifier
abstract class FieldInputType
{
    public const TEXT = 'text';
    public const TEXTAREA = 'textarea';
    public const DATE = 'date';
    public const NUMBER = 'number';
    public const BOOLEAN = 'boolean';
    public const URL = 'url';

    // TODO Remove this and add two tags, one where the available tags are specified at the collectible level, and one
    //      where the entries can be whatever the user wants (Freeform/Fixed?)
    public const TAGS = 'tags';

    /**
     * @return string[]
     */
    public static function getAvailableTypes(): array
    {
        return array_values((new \ReflectionClass(static::class))->getConstants());
    }
}
