<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_groups')->insert([
            'groupName' => 'Administrator',
            'permission' => '{"change-password":{"view":"1","edit":"1"},"dashboard":{"view":"1","create":"1","edit":"1","delete":"1"},"demo-pages":{"view":"1","create":"1","edit":"1","delete":"1"},"user-groups":{"view":1,"create":1,"edit":1,"delete":1},"users":{"view":"1","create":"1","edit":"1","delete":"1"},"activities-log":{"view":"1"}}',
            'createdAt' => Carbon::now()->toDateTimeString()
        ]);
    }
}
