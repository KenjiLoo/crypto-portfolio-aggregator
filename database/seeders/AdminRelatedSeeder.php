<?php

namespace Database\Seeders;

use App\Helpers\DBHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, Hash};

class AdminRelatedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin')->truncate();
        DB::table('admin')->insert([
            [
                'admin_profile_id' => 1,
                'username'         => 'admin',
                'password'         => Hash::make('TheAdmin.123!'),
                'name'             => 'SuperAdmin',
                'is_superadmin'    => true,
                'updated_by'       => 1,
            ],
        ]);
    }
}
