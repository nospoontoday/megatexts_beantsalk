<?php

namespace Database\Factories;

use App\Models\OnlineDatabase;
use Illuminate\Database\Eloquent\Factories\Factory;

class OnlineDatabaseFactory extends Factory
{
    public $product_id = 120;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OnlineDatabase::class;

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
