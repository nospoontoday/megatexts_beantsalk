<?php

namespace Database\Factories;

use App\Models\SalesOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalesOrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SalesOrder::class;    

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id' => rand(1,100),
            'branch_id' => rand(1,3),
            'order_summary' => 1,
            'total_amount' => 50,
            'status' => $this->faker->randomElement(['pending', 'topack', 'packed', 'delivered', 'served', 'cancelled']),
            'date' => $this->faker->dateTime(),          
            'reference_number'   => $this->faker->unique()->numberBetween(1, 99999),
            'iorf_number'   => $this->faker->unique()->numberBetween(1, 99999),
        ];
    }
}
