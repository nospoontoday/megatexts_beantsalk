<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PrintJournalFactory extends Factory
{
    public $product_id = 20;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => $this->incrementProductId(),
            'editor_id'         => rand(1,25),
            'issn'              => $this->faker->isbn13,
            'issue_number'      => $this->faker->randomNumber(5),
        ];
    }

    public function incrementProductId()
    {
        return $this->product_id++;
    }
}
