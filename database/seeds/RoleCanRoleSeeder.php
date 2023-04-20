<?php

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;

class RoleCanRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_can_role')->insert([
            'role_from_id' => Role::findByName('PPFF')->id,
            'role_to_id' => Role::findByName('Profesor tutor')->id,
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('role_can_role')->insert([
            'role_from_id' => Role::findByName('Profesor tutor')->id,
            'role_to_id' => Role::findByName('PPFF')->id,
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('role_can_role')->insert([
            'role_from_id' => Role::findByName('Profesor por horas')->id,
            'role_to_id' => Role::findByName('PPFF')->id,
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('role_can_role')->insert([
            'role_from_id' => Role::findByName('Admin')->id,
            'role_to_id' => Role::findByName('PPFF')->id,
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('role_can_role')->insert([
            'role_from_id' => Role::findByName('Admin')->id,
            'role_to_id' => Role::findByName('Profesor tutor')->id,
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('role_can_role')->insert([
            'role_from_id' => Role::findByName('Admin')->id,
            'role_to_id' => Role::findByName('Profesor por horas')->id,
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
