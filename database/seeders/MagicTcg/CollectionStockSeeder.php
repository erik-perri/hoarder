<?php

namespace Database\Seeders\MagicTcg;

use App\Models\Collectible;
use App\Models\Collection;
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
        $collection = Collection::firstOrFail();
        $stockItems = [
            ['Al-abara\'s Carpet', 'Legends', []],
            ['Eureka', 'Legends', ['language' => 'it']],
            ['Amulet of Unmaking', 'Mirage', []],
            ['Illusionary Presence', 'Ice Age', ['tags' => ['signed']]],
            ['Orim, Samite Healer', 'Tempest', []],
            ['Suleiman\'s Legacy', 'Visions', ['tags' => ['artist proof']]],
            ['Sustaining Spirit', 'Alliances', []],
            ['Sliver Queen', 'Stronghold', []],
            ['Sliver Queen', 'Stronghold', ['tags' => ['signed']]],
            ['Mox Diamond', 'Stronghold', []],
            ['Volrath\'s Stronghold', 'Stronghold', []],
            ['Dream Halls', 'Stronghold', []],
            ['Grave Pact', 'Stronghold', []],
            ['Ensnaring Bridge', 'Stronghold', []],
            ['Hermit Druid', 'Stronghold', []],
            ['Burgeoning', 'Stronghold', []],
            ['Volrath\'s Shapeshifter', 'Stronghold', []],
            ['Horn of Greed', 'Stronghold', []],
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
