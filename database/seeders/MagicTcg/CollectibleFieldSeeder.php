<?php

namespace Database\Seeders\MagicTcg;

use App\Collectible\Enum\FieldInputType;
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
                    'name' => 'Set Code',
                    'input_type' => FieldInputType::TEXT,
                    'is_required' => true,
                ],
                [
                    'name' => 'Released On',
                    'input_type' => FieldInputType::DATE,
                    'is_required' => false,
                ],
                [
                    'name' => 'Set Type',
                    'input_type' => FieldInputType::TEXT,
                    'is_required' => false,
                ],
                [
                    'name' => 'Card Count',
                    'input_type' => FieldInputType::NUMBER,
                    'is_required' => false,
                ],
                [
                    'name' => 'Digital',
                    'input_type' => FieldInputType::BOOLEAN,
                    'is_required' => false,
                ],
                [
                    'name' => 'Only Foil',
                    'input_type' => FieldInputType::BOOLEAN,
                    'is_required' => false,
                ],
                [
                    'name' => 'Only Non-Foil',
                    'input_type' => FieldInputType::BOOLEAN,
                    'is_required' => false,
                ],
                [
                    'name' => 'Logo URL',
                    'input_type' => FieldInputType::URL,
                    'is_required' => false,
                ],
            ],
            'item' => [
                [
                    'name' => 'Type Line',
                    'input_type' => FieldInputType::TEXT,
                    'is_required' => false,
                ],
                [
                    'name' => 'Oracle Text',
                    'input_type' => FieldInputType::TEXTAREA,
                    'is_required' => false,
                ],
                [
                    'name' => 'Flavor Text',
                    'input_type' => FieldInputType::TEXTAREA,
                    'is_required' => false,
                ],
                [
                    'name' => 'Power',
                    'input_type' => FieldInputType::TEXT,
                    'is_required' => false,
                ],
                [
                    'name' => 'Toughness',
                    'input_type' => FieldInputType::TEXT,
                    'is_required' => false,
                ],
                [
                    'name' => 'Colors',
                    'input_type' => FieldInputType::TAGS,
                    'is_required' => false,
                ],
                [
                    'name' => 'Color Identity',
                    'input_type' => FieldInputType::TAGS,
                    'is_required' => false,
                ],
                [
                    'name' => 'On Reserved List',
                    'input_type' => FieldInputType::BOOLEAN,
                    'is_required' => false,
                ],
                [
                    'name' => 'Collector Number',
                    'input_type' => FieldInputType::TEXT,
                    'is_required' => false,
                ],
                [
                    'name' => 'Rarity',
                    'input_type' => FieldInputType::TEXT,
                    'is_required' => false,
                ],
                [
                    'name' => 'Artist',
                    'input_type' => FieldInputType::TEXT,
                    'is_required' => false,
                ],
                [
                    'name' => 'Image URL',
                    'input_type' => FieldInputType::URL,
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
                    'code' => \Str::slug($fieldInfo['name'], '_'),
                ], $fieldInfo));

                $collectible->fields()->save($field);
            }
        }
    }
}
