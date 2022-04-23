<?php

namespace Database\Seeders;

use App\Models\AccessModel;
use Illuminate\Database\Seeder;

class AccessModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccessModel::factory()
            ->times(5)
            ->create();
    }
}
