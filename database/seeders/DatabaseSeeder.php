<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                PermissionTableSeeder::class,
                BranchSeeder::class,
                CreateAdminUserSeeder::class,
                CreateSalesRepSeeder::class,
                CategorySeeder::class,
                CustomerSeeder::class,
                VendorSeeder::class,
                PurposeSeeder::class,
                QuotationSeeder::class,
                ProductSeeder::class,
                SalesOrderSeeder::class,
                AuthorSeeder::class,
                PublisherSeeder::class,
                EditorSeeder::class,
                ManufacturerSeeder::class,
                AccessModelSeeder::class,
                PlatformSeeder::class,
                DeveloperSeeder::class,
                PrintBookSeeder::class,
                PrintJournalSeeder::class,
                AVMaterialSeeder::class,
                LibraryFixtureSeeder::class,
                EBookSeeder::class,
                EJournalSeeder::class,
                OnlineDatabaseSeeder::class,
                LibraryTechnologySeeder::class,
            ]
        );
    }
}
