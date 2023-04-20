<?php

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
        $this->call([UsersTableSeeder::class]);
        $this->call([StatementTableSeeder::class]);
        $this->call([StatementtypeTableSeeder::class]);
        $this->call([UserHasStatementTableSeeder::class]);
        $this->call([UserHasClassroomTableSeeder::class]);
        $this->call([ClassroomTableSeeder::class]);
        $this->call([PermissionsTableSeeder::class]);
        $this->call([ScholarPeriodSeeder::class]);
        $this->call([SlicesSeeder::class]);
        $this->call([ScholarPeriodHasSlicesSeeder::class]);
        $this->call([RoleCanRoleSeeder::class]);
        $this->call([UserHasUserSeeder::class]);
        $this->call([AttendanceTypeSeeder::class]);
    }
}
