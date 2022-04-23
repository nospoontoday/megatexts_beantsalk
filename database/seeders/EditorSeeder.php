<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Editor;

class EditorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Editor::factory()
            ->times(25)
            ->create();
    }
}
