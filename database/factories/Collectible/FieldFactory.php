<?php

namespace Database\Factories\Collectible;

use App\Models\Collectible;
use Illuminate\Database\Eloquent\Factories\Factory;

class FieldFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Collectible\Field::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $name = ucwords($this->faker->words(3, true));

        return [
            'entity_type' => $this->faker->randomElement(['category', 'item']),
            'code' => \Str::slug($name, '_'),
            'name' => $name,
            'input_type' => $this->faker->randomElement(['text', 'date', 'number']),
            'input_options' => [],
            'is_required' => $this->faker->boolean,
        ];
    }
}
