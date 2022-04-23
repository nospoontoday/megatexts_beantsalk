<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\SalesOrder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class SalesOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SalesOrder::factory()
            ->times(100)
            ->hasCustomer(1)
            ->state(new Sequence(
                ['branch_id' => 1, 'user_id' => 2],
                ['branch_id' => 2, 'user_id' => 3],
                ['branch_id' => 3, 'user_id' => 4],
            ))
            ->hasAttached(
                Product::factory()->count(1),
                ['quantity' => 1, 'price' => 50, 'discount' => 0]
            )
            ->create();
    }
}
