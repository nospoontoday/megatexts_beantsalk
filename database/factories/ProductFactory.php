<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->catchPhrase(),
            'quantity' => $this->faker->randomNumber(2),
            'price'   => $this->faker->randomNumber(2),
            'subject' => $this->faker->word,
            'type_id' => rand(1,8),
        ];
    }
}
