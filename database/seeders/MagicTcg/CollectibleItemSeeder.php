<?php

namespace Database\Seeders\MagicTcg;

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
        $collectible = Collectible::whereName(CollectibleSeeder::MAGIC_NAME)->firstOrFail();

        foreach (Collectible\Category::whereCollectibleId($collectible->id)
                                     ->orderBy('field_values->released_on')
                                     ->get() as $category) {
            /** @var Collectible\Category $category */
            $cards = $this->getCards($category->field_values['code']);

            foreach ($cards as $cardInfo) {
                Collectible\Item::factory()->create([
                    'collectible_id' => $collectible->id,
                    'category_id' => $category->id,
                    'name' => $cardInfo['name'],
                    'field_values' => array_filter([
                        'type_line' => $cardInfo['type_line'] ?? null,
                        'oracle_text' => $cardInfo['oracle_text'] ?? null,
                        'flavor_text' => $cardInfo['flavor_text'] ?? null,
                        'power' => $cardInfo['power'] ?? null,
                        'toughness' => $cardInfo['toughness'] ?? null,
                        'colors' => $cardInfo['colors'] ?? null,
                        'color_identity' => $cardInfo['color_identity'] ?? null,
                        'on_reserved_list' => $cardInfo['reserved'] ?? false,
                        'collector_number' => $cardInfo['collector_number'] ?? null,
                        'rarity' => $cardInfo['rarity'] ?? null,
                        'artist' => $cardInfo['artist'] ?? null,
                        'image_url' => $cardInfo['image_uris']['normal'] ?? $cardInfo['image_uris']['normal'] ?? null,
                    ]),
                ]);
            }
        }
    }

    private function getCards(string $setCode): array
    {
        $url = 'https://api.scryfall.com/cards/search?include_extras=true&unique=prints&q=e:'.$setCode;

        return Cache::remember(
                'magictcg.cards.'.md5($url),
                new \DateInterval('P1Y'),
                function () use ($url, $setCode) {
                    $results = [
                        'has_more' => true,
                        'next_page' => $url,
                    ];

                    $cardPages = [];

                    while ($results['has_more']) {
                        $nextUrl = $results['next_page'];
                        $response = Http::get($nextUrl);
                        $results = $response->json();

                        (new ConsoleOutput())->writeln(
                            'Downloaded '.number_format(strlen($response->body())).' bytes from '.$nextUrl
                        );

                        sleep(CollectibleSeeder::MAGIC_RATE_LIMIT);

                        if ($results['object'] === 'error') {
                            (new ConsoleOutput())->writeln(
                                '<error>'.$results['code'].' error</error> while fetching set '.$setCode
                            );

                            return [];
                        }

                        $cardPages[] = $results['data'];
                    }

                    return count($cardPages) ? array_merge(...$cardPages) : null;
                }
            ) ?? [];
    }
}
