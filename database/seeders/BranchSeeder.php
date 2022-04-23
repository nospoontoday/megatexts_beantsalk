<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branches = [
            ['name' => 'Makati'],
            ['name' => 'Cebu'],
            ['name' => 'Davao'],
        ];

        foreach($branches as $branch) {
            Branch::create(['name' => $branch['name']]);
        }
    }
}
