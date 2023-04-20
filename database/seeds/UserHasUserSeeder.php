<?php

use Illuminate\Database\Seeder;

class UserHasUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_has_user')->insert(['user_parent_id' => 7,'user_child_id' => 8,'scholarperiod_id' => 1,]);
        DB::table('user_has_user')->insert(['user_parent_id' => 7,'user_child_id' => 9,'scholarperiod_id' => 1,]);
    }
}
