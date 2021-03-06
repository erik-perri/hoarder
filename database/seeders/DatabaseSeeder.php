<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        // TODO Remove these, they are to help test whether our database structure is even viable.  The end goal is to
        //      have a UI to setup automatic syncs of collectible data with external sources (APIs, Official sites,
        //      Tcgplayer, etc).
        $this->call([
            CollectibleSeeder::class,
            CollectionSeeder::class,
            PokemonTcg\CollectibleFieldSeeder::class,
            PokemonTcg\CollectionGoalSeeder::class,
            PokemonTcg\CollectibleCategorySeeder::class,
            PokemonTcg\CollectibleItemSeeder::class,
            PokemonTcg\CollectionStockSeeder::class,
            MagicTcg\CollectibleFieldSeeder::class,
            MagicTcg\CollectionGoalSeeder::class,
            MagicTcg\CollectibleCategorySeeder::class,
            MagicTcg\CollectibleItemSeeder::class,
            MagicTcg\CollectionStockSeeder::class,
        ]);
    }
}
