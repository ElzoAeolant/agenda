<?php

use Illuminate\Database\Seeder;

class AttendanceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attendance_types')->insert([
            'name' => 'Inasistencia',
            'color' => 'success',
            'type' => 'unattendace',
            'min_hour' => '00:00',
            'max_hour' => '00:00',
            'require_justification' => true,
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('attendance_types')->insert([
            'name' => 'Entrada Normal',
            'level' => 'SECUNDARIA',
            'color' => 'info',
            'type' => 'checkin',
            'min_hour' => '07:00',
            'max_hour' => '07:35',
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('attendance_types')->insert([
            'name' => 'Entrada tardanza por justificar',
            'level' => 'SECUNDARIA',
            'require_justification' => true,
            'color' => 'danger',
            'type' => 'checkin',
            'min_hour' => '07:36',
            'max_hour' => '08:00',
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('attendance_types')->insert([
            'name' => 'Entrada tardanza por justificar / Extemporánea',
            'level' => 'SECUNDARIA',
            'require_justification' => true,
            'color' => 'primary',
            'type' => 'checkin',
            'min_hour' => '08:01',
            'max_hour' => '13:00',
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('attendance_types')->insert([
            'name' => 'Salida Normal',
            'level' => 'SECUNDARIA',
            'color' => 'info',
            'type' => 'checkout',
            'min_hour' => '13:30',
            'max_hour' => '15:00',
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        DB::table('attendance_types')->insert([
            'name' => 'Salida con tardanza',
            'level' => 'SECUNDARIA',
            'require_justification' => true,
            'color' => 'danger',
            'type' => 'checkout',
            'min_hour' => '15:01',
            'max_hour' => '16:30',
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('attendance_types')->insert([
            'name' => 'Entrada Normal',
            'level' => 'PRIMARIA',
            'color' => 'info',
            'type' => 'checkin',
            'min_hour' => '07:00',
            'max_hour' => '07:35',
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('attendance_types')->insert([
            'name' => 'Entrada tardanza por justificar',
            'level' => 'PRIMARIA',
            'require_justification' => true,
            'color' => 'danger',
            'type' => 'checkin',
            'min_hour' => '07:36',
            'max_hour' => '08:00',
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('attendance_types')->insert([
            'name' => 'Entrada tardanza por justificar / Extemporánea',
            'level' => 'PRIMARIA',
            'require_justification' => true,
            'color' => 'primary',
            'type' => 'checkin',
            'min_hour' => '08:01',
            'max_hour' => '13:00',
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('attendance_types')->insert([
            'name' => 'Salida Normal',
            'level' => 'PRIMARIA',
            'color' => 'info',
            'type' => 'checkout',
            'min_hour' => '13:30',
            'max_hour' => '15:00',
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        DB::table('attendance_types')->insert([
            'name' => 'Salida con tardanza',
            'level' => 'PRIMARIA',
            'require_justification' => true,
            'color' => 'danger',
            'type' => 'checkout',
            'min_hour' => '15:01',
            'max_hour' => '16:30',
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('attendance_types')->insert([
            'name' => 'Entrada Normal',
            'level' => 'INICIAL',
            'color' => 'info',
            'type' => 'checkin',
            'min_hour' => '08:00',
            'max_hour' => '08:30',
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('attendance_types')->insert([
            'name' => 'Entrada tardanza por justificar',
            'level' => 'INICIAL',
            'require_justification' => true,
            'color' => 'danger',
            'type' => 'checkin',
            'min_hour' => '08:31',
            'max_hour' => '09:00',
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('attendance_types')->insert([
            'name' => 'Entrada tardanza por justificar / Extemporánea',
            'level' => 'INICIAL',
            'color' => 'primary',
            'require_justification' => true,
            'type' => 'checkin',
            'min_hour' => '09:01',
            'max_hour' => '13:00',
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('attendance_types')->insert([
            'name' => 'Salida Normal',
            'level' => 'INICIAL',
            'color' => 'info',
            'type' => 'checkout',
            'min_hour' => '13:30',
            'max_hour' => '15:00',
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        DB::table('attendance_types')->insert([
            'name' => 'Salida con tardanza',
            'level' => 'INICIAL',
            'color' => 'danger',
            'type' => 'checkout',
            'require_justification' => true,
            'min_hour' => '15:01',
            'max_hour' => '16:30',
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('attendance_types')->insert([
            'name' => 'Escuela de familia',
            'color' => 'success',
            'type' => 'familyschools',
            'min_hour' => '18:30',
            'max_hour' => '20:00',
            'scholarperiod_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
    }
}
