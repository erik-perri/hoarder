<?php

namespace Database\Seeders\MagicTcg;

use App\Models\Collectible;
use Database\Seeders\CollectibleSeeder;
use Illuminate\Database\Seeder;

class CollectibleFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Throwable
     */
    public function run(): void
    {
        $collectible = Collectible::whereName(CollectibleSeeder::MAGIC_NAME)->firstOrFail();

        $fieldTypes = [
            'category' => [
                [
                    'code' => 'code',
                    'name' => 'Set Code',
                    'input_type' => 'text',
                    'is_required' => true,
                ],
                [
                    'code' => 'released_on',
                    'name' => 'Released On',
                    'input_type' => 'date',
                    'is_required' => false,
                ],
                [
                    'code' => 'set_type',
                    'name' => 'Set Type',
                    'input_type' => 'text',
                    'is_required' => false,
                ],
                [
                    'code' => 'card_count',
                    'name' => 'Card Count',
                    'input_type' => 'integer',
                    'is_required' => false,
                ],
                [
                    'code' => 'digital',
                    'name' => 'Digital',
                    'input_type' => 'boolean',
                    'is_required' => false,
                ],
                [
                    'code' => 'only_foil',
                    'name' => 'Only Foil',
                    'input_type' => 'boolean',
                    'is_required' => false,
                ],
                [
                    'code' => 'only_non_foil',
                    'name' => 'Only Non-Foil',
                    'input_type' => 'boolean',
                    'is_required' => false,
                ],
                [
                    'code' => 'logo_url',
                    'name' => 'Logo URL',
                    'input_type' => 'url',
                    'is_required' => false,
                ],
            ],
            'item' => [
                [
                    'code' => 'type_line',
                    'name' => 'Type Line',
                    'input_type' => 'text',
                    'is_required' => false,
                ],
                [
                    'code' => 'oracle_text',
                    'name' => 'Oracle Text',
                    'input_type' => 'textarea',
                    'is_required' => false,
                ],
                [
                    'code' => 'flavor_text',
                    'name' => 'Flavor Text',
                    'input_type' => 'textarea',
                    'is_required' => false,
                ],
                [
                    'code' => 'power',
                    'name' => 'Power',
                    'input_type' => 'text',
                    'is_required' => false,
                ],
                [
                    'code' => 'toughness',
                    'name' => 'Toughness',
                    'input_type' => 'text',
                    'is_required' => false,
                ],
                [
                    'code' => 'colors',
                    'name' => 'Colors',
                    'input_type' => 'tags',
                    'is_required' => false,
                ],
                [
                    'code' => 'color_identity',
                    'name' => 'Color Identity',
                    'input_type' => 'tags',
                    'is_required' => false,
                ],
                [
                    'code' => 'on_reserved_list',
                    'name' => 'On Reserved List',
                    'input_type' => 'boolean',
                    'is_required' => false,
                ],
                [
                    'code' => 'collector_number',
                    'name' => 'Collector Number',
                    'input_type' => 'text',
                    'is_required' => false,
                ],
                [
                    'code' => 'rarity',
                    'name' => 'Rarity',
                    'input_type' => 'text',
                    'is_required' => false,
                ],
                [
                    'code' => 'artist',
                    'name' => 'Artist',
                    'input_type' => 'text',
                    'is_required' => false,
                ],
                [
                    'code' => 'image_url',
                    'name' => 'Image URL',
                    'input_type' => 'url',
                    'is_required' => false,
                ],
            ],
        ];

        foreach ($fieldTypes as $entityType => $fields) {
            foreach ($fields as $fieldInfo) {
                /** @var Collectible\Field $field */
                $field = Collectible\Field::factory()->make(array_merge([
                    'collectible_id' => $collectible->id,
                    'entity_type' => $entityType,
                ], $fieldInfo));

                $collectible->fields()->save($field);
            }
        }
    }
}
