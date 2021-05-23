<?php

namespace Database\Seeders;

use App\Models\Collectible;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Throwable
     */
    public function run(): void
    {
        $user = User::firstOrFail();
        $collectible = Collectible::whereName(CollectibleSeeder::MAGIC_NAME)->firstOrFail();

        Collection::factory()->create([
            'user_id' => $user->id,
            'collectible_id' => $collectible->id,
            'name' => sprintf('%s Collection', CollectibleSeeder::MAGIC_NAME),
            'is_default' => true,
        ]);
    }
}
