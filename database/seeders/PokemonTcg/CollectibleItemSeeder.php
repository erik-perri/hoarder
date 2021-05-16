<?php

namespace Database\Seeders\PokemonTcg;

use App\Models\Collectible;
use Database\Seeders\CollectibleSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Output\ConsoleOutput;

class CollectibleItemSeeder extends Seeder
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

        foreach (Collectible\Category::whereCollectibleId($collectible->id)->get() as $category) {
            /** @var Collectible\Category $category */
            $cards = $this->getCards($category->field_values['code']);

            foreach ($cards as $cardInfo) {
                Collectible\Item::factory()->create([
                    'collectible_id' => $collectible->id,
                    'category_id' => $category->id,
                    'name' => $cardInfo['name'],
                    'field_values' => array_filter([
                        'types' => $cardInfo['types'] ?? null,
                        'supertype' => $cardInfo['supertype'] ?? null,
                        'subtypes' => $cardInfo['subtypes'] ?? null,
                        'hp' => $cardInfo['hp'] ?? null,
                        'collector_number' => $cardInfo['number'],
                        'rarity' => $cardInfo['rarity'] ?? null,
                        'artist' => $cardInfo['artist'] ?? null,
                        'image_url' => $cardInfo['images']['large'] ?? $cardInfo['images']['small'] ?? null,
                    ]),
                ]);
            }
        }
    }

    private function getCards(string $setCode): array
    {
        $url = 'https://api.pokemontcg.io/v2/cards?q=set.id:'.$setCode;

        return Cache::remember(
                'pokemontcg.cards.'.md5($url),
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
