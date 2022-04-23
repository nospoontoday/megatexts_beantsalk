<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $contactable = $this->contactable();

        return [
            'contactable_id'    => $contactable::factory(),
            'contactable_type'  => $contactable,
            'mobile' => $this->faker->phoneNumber,
        ];
    }

    public function contactable()
    {
        return $this->faker->randomElement([
            User::class,
            Customer::class,
        ]);
    }
}
