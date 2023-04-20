<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $details = '<p style="text-align: center;">&nbsp;&nbsp;<img src="https://jebp.edu.pe/web/images/jebp_logo.png" alt="LOGO" width="400" height="100" /></p>'.
            '<table style="border-collapse: collapse; width: 98.2079%; height: 336px;" border="1">'.
            '<tbody>'.
            '<tr style="height: 48px;">'.
            '<td style="width: 806px; text-align: center; height: 48px;" colspan="2"><span style="font-weight: bold; font-size: 18pt;"><span style="font-family: \'book antiqua\', palatino, serif;">DATOS PERSONALES</span></span></td>'.
            '</tr>'.
            '<tr style="height: 48px;">'.
            '<td style="width: 403px; height: 48px;">Contacto 1</td>'.
            '<td style="width: 403px; height: 48px;">'.
            '<p>Nombre</p>'.
            '<p>Tel&eacute;fono</p>'.
            '</td>'.
            '</tr>'.
            '<tr style="height: 48px;">'.
            '<td style="width: 403px; height: 48px;">Contacto 2</td>'.
            '<td style="width: 403px; height: 48px;">'.
            '<p>Nombre</p>'.
            '<p>Tel&eacute;fono</p>'.
            '</td>'.
            '</tr>'.
            '</tbody>'.
            '</table>'.
            '<p style="font-size: medium;">&nbsp;</p>'
        ;
        DB::table('users')->insert([
            'name' => 'Administrador técnico de la Agenda',
            'username'=>'admin.tech',
            'email' => 'admin.tech@agenda.jebp.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin.tech'),
            'details' => $details,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'name' => 'Administrador de contenido de la Agenda',
            'username'=>'admin.content',
            'email' => 'admin.content@agenda.jebp.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin.content'),
            'details' => $details,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'name' => 'Cesar Vallejo Vigo',
            'username'=>'cesar.vigo',
            'email' => 'cesar.vigo@agenda.jebp.com',
            'email_verified_at' => now(),
            'password' => Hash::make('cesar.vigo'),
            'details' => $details,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'name' => 'Convivencia Escolar',
            'username'=>'convivencia.escolar',
            'email' => 'convivencia.escolar@agenda.jebp.com',
            'email_verified_at' => now(),
            'password' => Hash::make('convivencia.escolar'),
            'details' => $details,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'name' => 'Psicología',
            'username'=>'psicologia',
            'email' => 'psicologia@agenda.jebp.com',
            'email_verified_at' => now(),
            'password' => Hash::make('psicologia'),
            'details' => $details,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'name' => 'Administrativo',
            'username'=>'administrativo',
            'email' => 'administrativo@agenda.jebp.com',
            'email_verified_at' => now(),
            'password' => Hash::make('administrativo'),
            'details' => $details,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'name' => 'Padre de Familia',
            'username'=>'dnippff',
            'email' => 'dnippff@agenda.jebp.com',
            'email_verified_at' => now(),
            'password' => Hash::make('dnippff'),
            'details' => $details,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'name' => 'Estudiante1',
            'username'=>'dniestudiante1',
            'email' => 'dniestudiante1@agenda.jebp.com',
            'email_verified_at' => now(),
            'password' => Hash::make('dniestudiante1'),
            'details' => $details,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'name' => 'Estudiante2',
            'username'=>'dniestudiante2',
            'email' => 'dniestudiante2@agenda.jebp.com',
            'email_verified_at' => now(),
            'password' => Hash::make('dniestudiante2'),
            'details' => $details,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
