<?php

use Illuminate\Database\Seeder;

class ScholarPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('scholar_period')->insert([
            'name' => 'Periodo Marzo21-Diciembre21',
            'begin_at' => '2021-03-01',
            'end_at' => '2021-12-28',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
