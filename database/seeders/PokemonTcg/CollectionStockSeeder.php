<?php

namespace Database\Seeders\PokemonTcg;

use App\Models\Collectible;
use App\Models\Collection;
use Database\Seeders\CollectibleSeeder;
use Illuminate\Database\Seeder;

class CollectionStockSeeder extends Seeder
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

        $stockItems = [
            ['Pikachu', 'Jungle', []],
            ['Pikachu', 'Skyridge', []],
            ['Pikachu', 'POP Series 4', []],
            ['Pikachu', 'Rising Rivals', []],
            ['Lt. Surge\'s Pikachu', 'Gym Challenge', []],
            ['Mewtwo', 'Base', []],
            ['Growlithe', 'Base', []],
            ['Ivysaur', 'Base', []],
            ['Raticate', 'Base', []],
            ['Metapod', 'Base', []],
            ['Nidoran ♂', 'Base', []],
            ['Poliwag', 'Base', []],
            ['Computer Search', 'Base', []],
            ['Pokédex', 'Base', []],
            ['Psychic Energy', 'Base', []],
        ];

        foreach ($stockItems as [$name, $category, $factoryData]) {
            $category = Collectible\Category::firstWhere('name', '=', $category);
            if (! $category) {
                throw new \Exception(sprintf('Failed to locate category "%s" for stock seed', $category));
            }

            $item = Collectible\Item::firstWhere([
                'name' => $name,
                'category_id' => $category->id,
            ]);
            if (! $item) {
                throw new \Exception(sprintf('Failed to locate "%s" in "%s" for stock seed', $name, $category));
            }

            Collection\Stock::factory()->create(array_merge($factoryData, [
                'collection_id' => $collection->id,
                'item_id' => $item->id,
            ]));
        }
    }
}
