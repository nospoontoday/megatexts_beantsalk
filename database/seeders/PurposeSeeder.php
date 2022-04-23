<?php

namespace Database\Seeders;

use App\Models\Purpose;
use Illuminate\Database\Seeder;

class PurposeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Purpose::create([
            'name' => 'RFQ'
        ]);

        Purpose::create([
            'name' => 'Public Tender'
        ]);

        Purpose::create([
            'name' => 'Customer Inquiry'
        ]);

        Purpose::create([
            'name' => 'Budgeting Purposes'
        ]);

        Purpose::create([
            'name' => 'Others'
        ]);
    }
}
