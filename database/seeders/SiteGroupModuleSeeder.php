<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteGroupModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $DB = DB::table('module');

        //create master module
        $dashboardMasterId = $DB->insertGetId([
            'master_id'     => 0,
            'type'          => 'site_group',
            'modulekey'     => 'master-home',
            'name'          => 'Dashboard',
            'sequence'      => 0,
            'is_superadmin' => false,
            'is_master'     => true,
        ]);

        //home
        $DB->insert([
            'master_id'     => $dashboardMasterId,
            'type'          => 'site_group',
            'modulekey'     => 'home',
            'name'          => 'Home',
            'sequence'      => 1,
            'is_superadmin' => false,
            'is_master'     => false,
        ]);

        $userMasterId = $DB->insertGetId([
            'master_id'     => 0,
            'type'          => 'site_group',
            'modulekey'     => 'master-user',
            'name'          => 'User',
            'sequence'      => 0,
            'is_superadmin' => false,
            'is_master'     => true,
        ]);

        //user
        $DB->insert([
            [
                'master_id'     => $userMasterId,
                'type'          => 'site_group',
                'modulekey'     => 'user',
                'name'          => 'Users',
                'sequence'      => 1,
                'is_superadmin' => false,
                'is_master'     => false,
            ],
            [
                'master_id'     => $userMasterId,
                'type'          => 'site_group',
                'modulekey'     => 'profile',
                'name'          => 'Profiles',
                'sequence'      => 2,
                'is_superadmin' => false,
                'is_master'     => false,
            ]
        ]);
    }
}
