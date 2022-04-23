<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LibraryFixtureFactory extends Factory
{
    public $product_id = 60;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'item_code'         => $this->faker->randomNumber(5),
            'product_id'        => $this->incrementProductId(),
            'manufacturer_id'   => rand(1,20),
            'dimension'         => '5x2x3',
            'vatable_sales'     => $this->faker->randomNumber(1),
            'vat'               => $this->faker->randomNumber(1),
        ];
    }

    public function incrementProductId()
    {
        return $this->product_id++;
    }
}
