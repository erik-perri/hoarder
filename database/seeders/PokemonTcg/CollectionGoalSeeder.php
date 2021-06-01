<?php

namespace Database\Seeders\PokemonTcg;

use App\Criteria\Comparison\Text;
use App\Models\Collection;
use Database\Seeders\CollectibleSeeder;
use Illuminate\Database\Seeder;

class CollectionGoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Throwable
     */
    public function run(): void
    {
        $collection = Collection::where('name', 'LIKE', CollectibleSeeder::POKEMON_NAME.'%')->firstOrFail();

        Collection\Goal::factory()->create([
            'collection_id' => $collection,
            'name' => 'Every Pikachu card',
            'category_criteria' => [],
            'item_criteria' => [
                [
                    'match_field' => 'name',
                    'match_comparison' => Text::COMPARISON_EQUALS,
                    'match_value' => 'Pikachu',
                ],
            ],
            'stock_criteria' => [],
        ]);

        Collection\Goal::factory()->create([
            'collection_id' => $collection,
            'name' => 'Every card in the base set',
            'category_criteria' => [
                [
                    'match_field' => 'set_code',
                    'match_comparison' => Text::COMPARISON_EQUALS,
                    'match_value' => 'base1',
                ],
            ],
            'item_criteria' => [],
            'stock_criteria' => [],
        ]);
    }
}
