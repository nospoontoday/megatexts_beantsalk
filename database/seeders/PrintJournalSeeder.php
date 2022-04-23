<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\PrintJournal;
use App\Models\Product;

class PrintJournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::where('type_id', 2)->get();

        foreach($products as $product) {
            PrintJournal::factory()
                ->for($product)
                ->create();
        }
    }
}
