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

        DB::table('admin_profile')->truncate();
        DB::table('admin_profile')->insert([
            [
                'name'          => 'Super Admin',
                'is_superadmin' => true,
                'updated_by'    => 1,
            ],
        ]);

        DB::table('admin_profile_module')->truncate();
        DBHelper::query("INSERT INTO admin_profile_module (admin_profile_id, module_id) SELECT 1, id FROM module WHERE type = 'admin'");

        DB::table('admin_module')->truncate();
        DB::table('admin_module')->insert([
            [
                'admin_id'  => 1,
                'module_id' => 2,
                'status'    => 'add'
            ],
            [
                'admin_id'  => 1,
                'module_id' => 7,
                'status'    => 'remove'
            ],
        ]);
    }
}
