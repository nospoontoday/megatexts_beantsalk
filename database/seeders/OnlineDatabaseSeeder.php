<?php

namespace Database\Seeders;

use App\Models\OnlineDatabase;
use App\Models\Product;
use Illuminate\Database\Seeder;


class OnlineDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::where('type_id', 7)->get();

        foreach($products as $product) {
            OnlineDatabase::factory()
                ->for($product)
                ->create();
        }
    }
}
