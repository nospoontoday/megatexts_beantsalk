<?php

namespace Database\Factories;

use App\Models\EBook;
use Illuminate\Database\Eloquent\Factories\Factory;

class EBookFactory extends Factory
{
    public $product_id = 80;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EBook::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => $this->incrementProductId(),
            'publisher_id' => rand(1,15),
            'author_id'  => rand(1,80),
            'e_isbn' => $this->faker->isbn13,
            'publication_year' => $this->faker->year($max = 'now'),
            'platform_id'   => rand(1,5),
            'access_model_id' => rand(1,5),
            
            
        ];
    }

    public function incrementProductId()
    {
        return $this->product_id++;
    }
}
