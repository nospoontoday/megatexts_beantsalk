<?php

namespace Database\Seeders;

use App\Models\LibraryFixture;
use App\Models\Product;
use Illuminate\Database\Seeder;

class LibraryFixtureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::where('type_id', 4)->get();

        foreach($products as $product) {
            LibraryFixture::factory()
                ->for($product)
                ->create();
        }
    }
}
