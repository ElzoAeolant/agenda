<?php

use Illuminate\Database\Seeder;

class SlicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('slices')->insert([
            'name' => 'Bimestre 1',
            'begin_at' => '2021-03-01',
            'end_at' => '2021-12-28',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('slices')->insert([
            'name' => 'Bimestre 2',
            'begin_at' => '2021-03-01',
            'end_at' => '2021-12-28',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('slices')->insert([
            'name' => 'Bimestre 3',
            'begin_at' => '2021-03-01',
            'end_at' => '2021-12-28',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('slices')->insert([
            'name' => 'Bimestre 4',
            'begin_at' => '2021-03-01',
            'end_at' => '2021-12-28',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
