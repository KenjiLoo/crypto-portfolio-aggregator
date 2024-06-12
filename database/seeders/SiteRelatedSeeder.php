<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiteRelatedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('site_group')->truncate();
        $siteGroupId = DB::table('site_group')->insertGetId([
            'name'         => 'ECL',
            'company_code' => 'ecl',
        ]);

        $DB = DB::table('site');
        $DB->truncate();
        $DB->insert([
            [
                'site_group_id' => $siteGroupId,
                'name'          => 'ECLBET MY',
                'site_code'     => 'eclbetMYR',
                'company_code'  => 'eclbet',
                'currency'      => 'MYR',
                'usd_rate'      => '4.612323',
            ],
            [
                'site_group_id' => $siteGroupId,
                'name'          => 'ECLBET SG',
                'site_code'     => 'eclbetSGD',
                'company_code'  => 'eclbet',
                'currency'      => 'SGD',
                'usd_rate'      => '1.612323',
            ],
            [
                'site_group_id' => $siteGroupId,
                'name'          => 'ECLBET VN',
                'site_code'     => 'eclbetVND',
                'company_code'  => 'eclbet',
                'currency'      => 'VND',
                'usd_rate'      => '350403.612323',
            ],
        ]);

        DB::table('site_profile')->truncate();
        $siteProfileId = DB::table('site_profile')->insertGetId([
            'site_group_id' => $siteGroupId,
            'name'          => 'Owner',
            'is_superadmin' => true,
        ]);

        $DB = DB::table('site_group_admin');
        $DB->truncate();
        $DB->insert([
            [
                'site_group_id'     => $siteGroupId,
                'site_profile_id'   => $siteProfileId,
                'username'          => 'ecl_admin_01',
                'password'          => Hash::make('Testing.123!'),
                'name'              => 'ECLAdmin01',
                'is_master_account' => true,
            ],
        ]);

        DB::table('site_profile_module')->truncate();
        $moduleIds = DB::table('module')->select('id')->where('type', '=', 'site_group')->pluck('id')->toArray();
        foreach ($moduleIds as $moduleId) {
            DB::table('site_profile_module')->insert([
                [
                    'site_profile_id' => $siteProfileId,
                    'module_id'       => $moduleId,
                ]
            ]);
        }

        DB::table('site_group_admin_site')->truncate();
        DB::table('site_group_admin_site')->insert([
            [
                'site_group_admin_id' => 1,
                'site_id'             => 1,
            ]
        ]);
    }
}
