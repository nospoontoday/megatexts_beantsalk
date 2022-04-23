<?php

namespace Database\Seeders;

use App\Models\Category;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category1 = Category::create(['name' => 'Print']);
        $category2 = Category::create(['name' => 'Digital']);

        $category1->types()->createMany([
            ['name' => 'print-books'],
            ['name' => 'print-journals'],
            ['name' => 'AV-materials'],
            ['name' => 'library-fixtures'],
        ]);

        $category2->types()->createMany([
            ['name' => 'e-books'],
            ['name' => 'e-journals'],
            ['name' => 'online-databases'],
            ['name' => 'library-technologies'],
        ]);
    }
}
