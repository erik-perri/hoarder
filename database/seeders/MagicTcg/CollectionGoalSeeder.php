<?php

namespace Database\Seeders\MagicTcg;

use App\Criteria\Comparison\Boolean;
use App\Criteria\Comparison\Number;
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
        $collection = Collection::where('name', 'LIKE', CollectibleSeeder::MAGIC_NAME.'%')->firstOrFail();

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
                    'match_field' => 'set_code',
                    'match_comparison' => Text::COMPARISON_EQUALS,
                    'match_value' => 'sth',
                ],
            ],
            'item_criteria' => [],
            'stock_criteria' => [],
        ]);

        Collection\Goal::factory()->create([
            'collection_id' => $collection,
            'name' => 'Sibling Artist Collaborations (Playset)',
            'category_criteria' => [],
            'item_criteria' => [
                [
                    'group_type' => 'or',
                    'group_conditions' => [
                        [
                            'group_type' => 'and',
                            'group_conditions' => [
                                [
                                    'match_field' => 'artist',
                                    'match_comparison' => Text::COMPARISON_CONTAINS,
                                    'match_value' => 'Kaja',
                                ],
                                [
                                    'match_field' => 'artist',
                                    'match_comparison' => Text::COMPARISON_CONTAINS,
                                    'match_value' => 'Phil',
                                ],
                                [
                                    'match_field' => 'artist',
                                    'match_comparison' => Text::COMPARISON_CONTAINS,
                                    'match_value' => 'Foglio',
                                ],
                            ],
                        ],
                        [
                            'group_type' => 'and',
                            'group_conditions' => [
                                [
                                    'match_field' => 'artist',
                                    'match_comparison' => Text::COMPARISON_CONTAINS,
                                    'match_value' => 'Ron Spencer',
                                ],
                                [
                                    'match_field' => 'artist',
                                    'match_comparison' => Text::COMPARISON_CONTAINS,
                                    'match_value' => 'Terese Nielsen',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'stock_criteria' => [
                [
                    'match_field' => 'count',
                    'match_comparison' => Number::COMPARISON_GREATER_THAN_OR_EQUAL,
                    'match_value' => 4,
                ],
                [
                    'group_type' => 'or',
                    'group_conditions' => [
                        [
                            'match_field' => 'condition',
                            'match_comparison' => Text::COMPARISON_EQUALS,
                            'match_value' => 'Near Mint',
                        ],
                        [
                            'match_field' => 'condition',
                            'match_comparison' => Text::COMPARISON_EQUALS,
                            'match_value' => 'Lightly Played',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
