<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\LibraryTechnology;
use App\Models\Product;

class LibraryTechnologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::where('type_id', 8)->get();

        foreach($products as $product) {
            LibraryTechnology::factory()
                ->for($product)
                ->create();
        }
    }
}
