<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $addressable = $this->addressable();

        return [
            'addressable_id'    => $addressable::factory(),
            'addressable_type'  => $addressable,
            'email'             => $this->faker->email,
            'website'           => $this->faker->url,
            'present_address'   => $this->faker->address,
            'city'              => $this->faker->city,
            'state'             => $this->faker->state,
            'zip'               => $this->faker->numberBetween($min = 1000, $max = 9000),
        ];
    }

    public function addressable()
    {
        return $this->faker->randomElement([
            User::class,
            Customer::class,
        ]);
    }
}
