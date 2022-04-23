<?php

namespace Database\Factories;

use App\Models\Quotation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuotationFactory extends Factory
{

    private static $number = 0;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Quotation::class;    

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $pr_number = generate_unique_id("Q", Carbon::now()->year, "-", self::$number++, Carbon::now()->year);

        return [
            'project_title' => $this->faker->unique()->company,
            'pr_number'   => $pr_number,
            'deadline' => $this->faker->dateTime(),
            'bidding_date' => $this->faker->dateTime(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'cancelled']),
            'purpose_id' => rand(1,5),
        ];
    }
}
