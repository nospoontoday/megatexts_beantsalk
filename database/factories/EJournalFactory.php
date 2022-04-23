<?php

namespace Database\Factories;

use App\Models\EJournal;
use Illuminate\Database\Eloquent\Factories\Factory;

class EJournalFactory extends Factory
{
    public $product_id = 100;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EJournal::class;

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
            'editor_id'  => rand(1,25),
            'e_issn' => $this->faker->isbn13,
            'frequency' => $this->faker->year($max = 'now'),
            'platform_id'   => rand(1,5),
            'access_model_id' => rand(1,5),
            'subscription_period' => $this->faker->dateTimeBetween('now', '+3 years'),
        ];
    }

    public function incrementProductId()
    {
        return $this->product_id++;
    }
}
