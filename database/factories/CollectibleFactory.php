<?php

namespace Database\Factories;

use App\Models\Collectible;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollectibleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Collectible::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
        ];
    }
}
