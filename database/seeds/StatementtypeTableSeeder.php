<?php

use Illuminate\Database\Seeder;

class StatementtypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statement_types')->insert([
            'name' => 'Mensaje a tutor',
            'color' => 'info',
            'default' => 'Se informa que...',
        ]);
        DB::table('statement_types')->insert([
            'name' => 'Incidencia',
            'color' => 'danger',
            'default' => 'El alumno hizo...',
        ]);
        DB::table('statement_types')->insert([
            'name' => 'Incumplimiento',
            'color' => 'danger',
            'default' => 'El alumno no cumplió...',
        ]);
        DB::table('statement_types')->insert([
            'name' => 'Citación',
            'color' => 'danger',
            'default' => 'Requerimos su asistencia...',
        ]);
        DB::table('statement_types')->insert([
            'name' => 'Felicitación',
            'color' => 'success',
            'default' => 'Felicitamos a su hijo...',
        ]);
        DB::table('statement_types')->insert([
            'name' => 'Tarea',
            'color' => 'success',
            'default' => 'Tarea para el lunes próximo...',
        ]);
        DB::table('statement_types')->insert([
            'name' => 'Notificación',
            'color' => 'success',
            'default' => 'Se notifica que...',
        ]);
        DB::table('statement_types')->insert([
            'name' => 'Recordatorio',
            'color' => 'success',
            'default' => 'Se notifica que...',
        ]);
        DB::table('statement_types')->insert([
            'name' => 'Evento',
            'color' => 'success',
            'default' => 'Se notifica que...',
        ]);
        DB::table('statement_types')->insert([
            'name' => 'Tardanza',
            'color' => 'danger',
            'default' => 'Se notifica que...',
            'type' => 'ATTENDANCE'
        ]);
        DB::table('statement_types')->insert([
            'name' => 'Tardanza Justificada',
            'color' => 'danger',
            'default' => 'Se notifica que...',
            'type' => 'ATTENDANCE'
        ]);
        DB::table('statement_types')->insert([
            'name' => 'Inasistencia',
            'color' => 'danger',
            'default' => 'Se notifica que...',
            'type' => 'ATTENDANCE'
        ]);
        DB::table('statement_types')->insert([
            'name' => 'Inasistencia Justificada',
            'color' => 'danger',
            'default' => 'Se notifica que...',
            'type' => 'ATTENDANCE'
        ]);
    }
}
