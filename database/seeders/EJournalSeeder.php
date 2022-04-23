<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\EJournal;
use App\Models\Product;

class EJournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::where('type_id', 6)->get();

        foreach($products as $product) {
            EJournal::factory()
                ->for($product)
                ->create();
        }
    }
}
