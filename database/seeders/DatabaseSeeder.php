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
        $this->call(ModuleSeeder::class);
        $this->call(SiteGroupModuleSeeder::class);
        $this->call(AdminRelatedSeeder::class);
        $this->call(SiteRelatedSeeder::class);
    }
}
