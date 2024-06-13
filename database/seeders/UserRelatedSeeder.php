<?php

namespace Database\Seeders;

use App\Helpers\DBHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, Hash};

class UserRelatedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user')->truncate();
        DB::table('user')->insert([
            [
                'username'         => 'user',
                'password'         => Hash::make('asdf1234'),
                'name'             => 'First User (Kenji)'
            ],
        ]);
    }
}
