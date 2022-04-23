<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LibraryTechnologyFactory extends Factory
{
    public $product_id = 140;
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
            'developer_id'      => rand(1,10),
            'subscription_period' => $this->faker->dateTimeBetween('now', '+3 years'),
            'vatable_sales'     => $this->faker->randomNumber(1),
            'vat'               => $this->faker->randomNumber(1),
        ];
    }

    public function incrementProductId()
    {
        return $this->product_id++;
    }
}
