<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\PrintBook;
use App\Models\Product;

class PrintBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::where('type_id', 1)->get();

        foreach($products as $product) {
            PrintBook::factory()
                ->for($product)
                ->create();
        }
    }
}
