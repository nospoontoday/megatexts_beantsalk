<?php

namespace Database\Factories;

use App\Models\AVMaterial;
use Illuminate\Database\Eloquent\Factories\Factory;

class AVMaterialFactory extends Factory
{
    public $product_id = 40;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AVMaterial::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'item_code' => $this->faker->randomNumber(6),
            'publication_year' => $this->faker->year($max = 'now'),
            'discount'   => $this->faker->randomNumber(1),
            'product_id' => $this->incrementProductId(),
            'author_id'  => rand(1,80),
            'publisher_id' => rand(1,15),
        ];
    }

    public function incrementProductId()
    {
        return $this->product_id++;
    }
}
