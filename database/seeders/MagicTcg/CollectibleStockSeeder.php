<?php

namespace Database\Seeders\MagicTcg;

use App\Models\Collectible;
use App\Models\User;
use Illuminate\Database\Seeder;

class CollectibleStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Throwable
     */
    public function run(): void
    {
        $user = User::first();
        if (! $user) {
            throw new \Exception('No user');
        }

        $stockItems = [
            ['Al-abara\'s Carpet', 'Legends', []],
            ['Eureka', 'Legends', ['language' => 'it']],
            ['Amulet of Unmaking', 'Mirage', []],
            ['Illusionary Presence', 'Ice Age', ['tags' => ['signed']]],
            ['Orim, Samite Healer', 'Tempest', []],
            ['Suleiman\'s Legacy', 'Visions', ['tags' => ['artist proof']]],
            ['Sustaining Spirit', 'Alliances', []],
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

            Collectible\Stock::factory()->create(array_merge($factoryData, [
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]));
        }
    }
}
