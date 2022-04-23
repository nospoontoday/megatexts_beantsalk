<?php

namespace Database\Factories;

use App\Models\PrintBook;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrintBookFactory extends Factory
{
    public $product_id = 1;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PrintBook::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'isbn_13' => $this->faker->isbn13,
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
