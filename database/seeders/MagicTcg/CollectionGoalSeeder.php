<?php

namespace Database\Seeders\MagicTcg;

use App\Criteria\Comparison\Boolean;
use App\Criteria\Comparison\Text;
use App\Models\Collection;
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
        $collection = Collection::firstOrFail();

        Collection\Goal::factory()->create([
            'collection_id' => $collection,
            'name' => 'Every Rebecca Guay and Kaja Foglio card on the reserved list',
            'category_criteria' => [
                [
                    'match_field' => 'digital',
                    'match_comparison' => Boolean::COMPARISON_IS,
                    'match_value' => Boolean::VALUE_FALSE,
                ],
            ],
            'item_criteria' => [
                [
                    'group_type' => 'or',
                    'group_conditions' => [
                        [
                            'match_field' => 'artist',
                            'match_comparison' => Text::COMPARISON_EQUALS,
                            'match_value' => 'Rebecca Guay',
                        ],
                        [
                            'match_field' => 'artist',
                            'match_comparison' => Text::COMPARISON_EQUALS,
                            'match_value' => 'Kaja Foglio',
                        ],
                    ],
                ],
                [
                    'match_field' => 'on_reserved_list',
                    'match_comparison' => Boolean::COMPARISON_IS,
                    'match_value' => Boolean::VALUE_TRUE,
                ],
            ],
            'stock_criteria' => [],
        ]);

        Collection\Goal::factory()->create([
            'collection_id' => $collection,
            'name' => 'Every card on the reserved list',
            'category_criteria' => [
                [
                    'match_field' => 'digital',
                    'match_comparison' => Boolean::COMPARISON_IS,
                    'match_value' => Boolean::VALUE_FALSE,
                ],
            ],
            'item_criteria' => [
                [
                    'match_field' => 'on_reserved_list',
                    'match_comparison' => Boolean::COMPARISON_IS,
                    'match_value' => Boolean::VALUE_TRUE,
                ],
            ],
            'stock_criteria' => [],
        ]);

        Collection\Goal::factory()->create([
            'collection_id' => $collection,
            'name' => 'Every card in Stronghold',
            'category_criteria' => [
                [
                    'match_field' => 'code',
                    'match_comparison' => Text::COMPARISON_EQUALS,
                    'match_value' => 'sth',
                ],
            ],
            'item_criteria' => [],
            'stock_criteria' => [],
        ]);
    }
}
