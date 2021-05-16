<?php

namespace Database\Seeders\PokemonTcg;

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
        $collectible = Collectible::whereName(CollectibleSeeder::POKEMON_NAME)->firstOrFail();

        $fieldTypes = [
            'category' => [
                [
                    'code' => 'code',
                    'name' => 'Set Code',
                    'input_type' => 'text',
                    'is_required' => true,
                ],
                [
                    'code' => 'ptcgo_code',
                    'name' => 'Online Set Code',
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
                    'code' => 'series',
                    'name' => 'Series',
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
                    'code' => 'logo_url',
                    'name' => 'Logo URL',
                    'input_type' => 'url',
                    'is_required' => false,
                ],
            ],
            'item' => [
                [
                    'code' => 'types',
                    'name' => 'Types',
                    'input_type' => 'tags',
                    'is_required' => false,
                ],
                [
                    'code' => 'supertype',
                    'name' => 'Supertype',
                    'input_type' => 'text',
                    'is_required' => false,
                ],
                [
                    'code' => 'subtypes',
                    'name' => 'Subtypes',
                    'input_type' => 'tags',
                    'is_required' => false,
                ],
                [
                    'code' => 'hp',
                    'name' => 'HP',
                    'input_type' => 'integer',
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
