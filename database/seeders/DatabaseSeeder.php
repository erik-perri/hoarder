<?php

namespace Database\Seeders;

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
        // TODO Remove these, they are to help test whether our database structure is even viable.  The end goal is to
        //      have a UI to setup automatic syncs of collectible data with external sources (APIs, Official sites,
        //      Tcgplayer, etc).
        $this->call([
            CollectibleSeeder::class,
            MagicTcg\CollectibleFieldSeeder::class,
            MagicTcg\CollectibleCategorySeeder::class,
            MagicTcg\CollectibleItemSeeder::class,
            PokemonTcg\CollectibleFieldSeeder::class,
            PokemonTcg\CollectibleCategorySeeder::class,
            PokemonTcg\CollectibleItemSeeder::class,
        ]);
    }
}
