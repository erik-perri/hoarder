<?php

namespace Database\Factories\Collectible;

use App\Models\Collectible;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Collectible\Stock::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'count' => $this->faker->numberBetween(1, 10),
            'condition' => $this->faker->randomElement([
                'Mint',
                'Near Mint',
                'Good (Lightly Played)',
                'Played',
                'Heavily Played',
                'Poor',
            ]),
            'language' => 'en',
            'tags' => [],
        ];
    }
}
