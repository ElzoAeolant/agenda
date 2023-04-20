<?php

use Illuminate\Database\Seeder;

class ScholarPeriodHasSlicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('scholarperiod_has_slice')->insert([
            'scholarperiod_id' => 1,
            'slice_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('scholarperiod_has_slice')->insert([
            'scholarperiod_id' => 1,
            'slice_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('scholarperiod_has_slice')->insert([
            'scholarperiod_id' => 1,
            'slice_id' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('scholarperiod_has_slice')->insert([
            'scholarperiod_id' => 1,
            'slice_id' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
