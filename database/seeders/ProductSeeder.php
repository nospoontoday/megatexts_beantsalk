<?php

namespace Database\Seeders;

use App\Models\PrintBook;
use Illuminate\Database\Seeder;

use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()
            ->times(160)
            ->create();
    }
}
