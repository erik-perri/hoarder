<?php

namespace Database\Seeders\MagicTcg;

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
                    'match_comparison' => 'boolean_is',
                    'match_value' => 'false',
                ],
            ],
            'item_criteria' => [
                [
                    'group_type' => 'or',
                    'group_conditions' => [
                        [
                            'match_field' => 'artist',
                            'match_comparison' => 'text_equals',
                            'match_value' => 'Rebecca Guay',
                        ],
                        [
                            'match_field' => 'artist',
                            'match_comparison' => 'text_equals',
                            'match_value' => 'Kaja Foglio',
                        ],
                    ],
                ],
                [
                    'match_field' => 'on_reserved_list',
                    'match_comparison' => 'boolean_is',
                    'match_value' => 'true',
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
                    'match_comparison' => 'boolean_is',
                    'match_value' => 'false',
                ],
            ],
            'item_criteria' => [
                [
                    'match_field' => 'on_reserved_list',
                    'match_comparison' => 'boolean_is',
                    'match_value' => 'true',
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
                    'match_comparison' => 'text_equals',
                    'match_value' => 'sth',
                ],
            ],
            'item_criteria' => [],
            'stock_criteria' => [],
        ]);
    }
}
