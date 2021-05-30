<?php

namespace Database\Seeders\MagicTcg;

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
        $collectible = Collectible::whereName(CollectibleSeeder::MAGIC_NAME)->firstOrFail();

        foreach ($this->getSets() as $editionInfo) {
            /* @var Collectible\Category $category */
            Collectible\Category::factory()->create([
                'collectible_id' => $collectible->id,
                'name' => $editionInfo['name'],
                'field_values' => array_filter([
                    'set_code' => $editionInfo['code'],
                    'released_on' => (new \DateTime($editionInfo['released_at'],
                        new \DateTimeZone('America/Los_Angeles')))->format('Y-m-d'),
                    'set_type' => $editionInfo['set_type'],
                    'card_count' => $editionInfo['card_count'],
                    'digital' => $editionInfo['digital'],
                    'only_foil' => $editionInfo['foil_only'],
                    'only_non_foil' => $editionInfo['nonfoil_only'],
                    'logo_url' => $editionInfo['icon_svg_uri'] ?? null,
                ], static fn ($v) => $v !== null),
            ]);
        }
    }

    private function getSets(): array
    {
        $url = 'https://api.scryfall.com/sets';

        return Cache::remember(
                'magictcg.sets.'.md5($url),
                new \DateInterval('P1Y'),
                function () use ($url) {
                    $response = Http::get($url);
                    $results = $response->json();

                    (new ConsoleOutput())->writeln(
                        'Downloaded '.number_format(strlen($response->body())).' bytes from '.$url
                    );

                    if ($results['has_more']) {
                        throw new \Exception('Category pages not implemented');
                    }

                    sleep(CollectibleSeeder::MAGIC_RATE_LIMIT);

                    return $results['data'] ?? null;
                }
            ) ?? [];
    }
}
