<?php

namespace Database\Seeders;

use App\Models\Collectible;
use App\Models\User;
use Illuminate\Database\Seeder;

class CollectibleSeeder extends Seeder
{
    public const MAGIC_NAME = 'Magic: The Gathering';
    public const MAGIC_RATE_LIMIT = 1; // https://scryfall.com/docs/api (10/sec)
    public const POKEMON_NAME = 'Pokémon TCG';
    public const POKEMON_RATE_LIMIT = 3; // https://docs.pokemontcg.io/#documentationrate_limits (30/m)

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

        Collectible::factory()->create([
            'created_by_id' => $user->id,
            'name' => static::MAGIC_NAME,
        ]);

        Collectible::factory()->create([
            'created_by_id' => $user->id,
            'name' => static::POKEMON_NAME,
        ]);
    }
}
