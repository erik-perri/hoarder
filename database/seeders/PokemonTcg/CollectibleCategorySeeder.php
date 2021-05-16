<?php

namespace Database\Seeders\PokemonTcg;

use App\Models\Collectible;
use Database\Seeders\CollectibleSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Output\ConsoleOutput;

class CollectibleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Throwable
     */
    public function run(): void
    {
        $collectible = Collectible::whereName(CollectibleSeeder::POKEMON_NAME)->firstOrFail();

        foreach ($this->getSets() as $editionInfo) {
            /* @var Collectible\Category $category */
            Collectible\Category::factory()->create([
                'collectible_id' => $collectible->id,
                'name' => $editionInfo['name'],
                'field_values' => array_filter([
                    'code' => $editionInfo['id'],
                    'ptcgo_code' => $editionInfo['ptcgoCode'] ?? null,
                    'released_on' => new \DateTime($editionInfo['releaseDate'],
                        new \DateTimeZone('America/Los_Angeles')),
                    'series' => $editionInfo['series'],
                    'card_count' => $editionInfo['total'],
                    'logo_url' => $editionInfo['images']['logo'] ?? null,
                ]),
            ]);
        }
    }

    private function getSets(): array
    {
        $url = 'https://api.pokemontcg.io/v2/sets';

        return Cache::remember(
                'pokemontcg.sets.'.md5($url),
                new \DateInterval('P1Y'),
                function () use ($url) {
                    $response = Http::get($url);
                    $results = $response->json();

                    (new ConsoleOutput())->writeln(
                        'Downloaded '.number_format(strlen($response->body())).' bytes from '.$url
                    );

                    sleep(CollectibleSeeder::POKEMON_RATE_LIMIT);

                    return $results['data'] ?? null;
                }
            ) ?? [];
    }
}
