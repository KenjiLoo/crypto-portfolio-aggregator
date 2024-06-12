<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $DB = DB::table('module');
        $DB->truncate();

        //create master module
        $dashboardMasterId = $DB->insertGetId([
            'master_id'     => 0,
            'type'          => 'admin',
            'modulekey'     => 'master-home',
            'name'          => 'Dashboard',
            'sequence'      => 0,
            'is_superadmin' => false,
            'is_master'     => true,
        ]);

        //home
        $DB->insert([
            'master_id'     => $dashboardMasterId,
            'type'          => 'admin',
            'modulekey'     => 'home',
            'name'          => 'Home',
            'sequence'      => 1,
            'is_superadmin' => false,
            'is_master'     => false,
        ]);

        $adminMasterId = $DB->insertGetId([
            'master_id'     => 0,
            'type'          => 'admin',
            'modulekey'     => 'master-admin',
            'name'          => 'Admin',
            'sequence'      => 0,
            'is_superadmin' => false,
            'is_master'     => true,
        ]);

        //admin
        $DB->insert([
            [
                'master_id'     => $adminMasterId,
                'type'          => 'admin',
                'modulekey'     => 'admin',
                'name'          => 'Admins',
                'sequence'      => 1,
                'is_superadmin' => false,
                'is_master'     => false,
            ],
            [
                'master_id'     => $adminMasterId,
                'type'          => 'admin',
                'modulekey'     => 'admin-profile',
                'name'          => 'Admin Profiles',
                'sequence'      => 2,
                'is_superadmin' => false,
                'is_master'     => false,
            ],
            [
                'master_id'     => $adminMasterId,
                'type'          => 'admin',
                'modulekey'     => 'audit-log',
                'name'          => 'Audit Log',
                'sequence'      => 3,
                'is_superadmin' => false,
                'is_master'     => false,
            ]
        ]);

        $moduleMasterId = $DB->insertGetId([
            'master_id'     => 0,
            'type'          => 'admin',
            'modulekey'     => 'master-module',
            'name'          => 'Module',
            'sequence'      => 0,
            'is_superadmin' => false,
            'is_master'     => true,
        ]);

        //module
        $DB->insert([
            'master_id'     => $moduleMasterId,
            'type'          => 'admin',
            'modulekey'     => 'module',
            'name'          => 'Modules',
            'sequence'      => 1,
            'is_superadmin' => false,
            'is_master'     => false,
        ]);

        $siteMasterId = $DB->insertGetId([
            'master_id'     => 0,
            'type'          => 'admin',
            'modulekey'     => 'master-site',
            'name'          => 'Site',
            'sequence'      => 0,
            'is_superadmin' => false,
            'is_master'     => true,
        ]);

        //site
        $DB->insert([
            [
                'master_id'     => $siteMasterId,
                'type'          => 'admin',
                'modulekey'     => 'site',
                'name'          => 'Sites',
                'sequence'      => 1,
                'is_superadmin' => false,
                'is_master'     => false,
            ],
            [
                'master_id'     => $siteMasterId,
                'type'          => 'admin',
                'modulekey'     => 'site-group',
                'name'          => 'Site Groups',
                'sequence'      => 2,
                'is_superadmin' => false,
                'is_master'     => false,
            ]
        ]);
    }
}
