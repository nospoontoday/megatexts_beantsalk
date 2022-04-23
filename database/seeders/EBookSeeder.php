<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\EBook;
use App\Models\Product;

class EBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::where('type_id', 5)->get();

        foreach($products as $product) {
            EBook::factory()
                ->for($product)
                ->create();
        }
    }
}
