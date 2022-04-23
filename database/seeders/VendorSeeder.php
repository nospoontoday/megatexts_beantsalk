<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vendor::factory()
            ->times(100)
            ->hasAddresses(1)
            ->hasContacts(1)
            ->create();
    }
}
