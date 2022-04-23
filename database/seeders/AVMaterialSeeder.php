<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\AVMaterial;
use App\Models\Product;

class AVMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::where('type_id', 3)->get();

        foreach($products as $product) {
            AVMaterial::factory()
                ->for($product)
                ->create();
        }
    }
}
