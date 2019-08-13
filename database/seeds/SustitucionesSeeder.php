<?php

use Illuminate\Database\Seeder;

class SustitucionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['tipo_persona_id' => '1', 'nombre' => 'Transferencia por compra venta', 'descripcion' => 'Transferencia por compra venta del predio agricola'],
            ['tipo_persona_id' => '1', 'nombre' => 'Transferencia por sucesión', 'descripcion' => 'Transferencia por sucesión hereditaria del predio agricola'],
            ['tipo_persona_id' => '1', 'nombre' => 'Reasignación por renuncia', 'descripcion' => 'Reasignación por renuncia voluntaria de manera escrita por el productor de coca'],
            ['tipo_persona_id' => '1', 'nombre' => 'Reasignación por abandono', 'descripcion' => 'Reasignación por abandono de la parcela productiva o incumplimiento de la función social por parte del productor mayor a un (1) año y de acuerdo a sus normas y procedimientos propios de la organización a la que pertenece'],
            ['tipo_persona_id' => '1', 'nombre' => 'Reasignación por sentencia', 'descripcion' => 'Reasignación por sentencia penal ejecutoriada, relacionada a delitos de sustancias controladas'],
            ['tipo_persona_id' => '1', 'nombre' => 'Otros', 'descripcion' => 'Otros'],
            ['tipo_persona_id' => '2', 'nombre' => 'Sucesión hereditaria', 'descripcion' => 'Sucesión hereditaria hasta el segundo grado consanguineo en linea ascendente o descendente'],
            ['tipo_persona_id' => '2', 'nombre' => 'Disposición del titular', 'descripcion' => 'Disposición del titular dentro de la comunidad, sindicato, federacion o confederación'],
            ['tipo_persona_id' => '2', 'nombre' => 'Inactividad', 'descripcion' => 'Inactividad en la comercialización de la hoja de coca durante doce (12) meses continuos sin justificación alguna, previo reporte anul del Viceministerio de Coca y Desarrollo Integral'],
            ['tipo_persona_id' => '2', 'nombre' => 'Suspención definitiva', 'descripcion' => 'Suspención definitiva del titular derivada de proceso administrativo'],

            ['tipo_persona_id' => '3', 'nombre' => 'Transferencia por compra venta', 'descripcion' => 'Transferencia por compra venta del predio agricola'],
            ['tipo_persona_id' => '3', 'nombre' => 'Transferencia por sucesión', 'descripcion' => 'Transferencia por sucesión hereditaria del predio agricola'],
            ['tipo_persona_id' => '3', 'nombre' => 'Reasignación por renuncia', 'descripcion' => 'Reasignación por renuncia voluntaria de manera escrita por el productor de coca'],
            ['tipo_persona_id' => '3', 'nombre' => 'Reasignación por abandono', 'descripcion' => 'Reasignación por abandono de la parcela productiva o incumplimiento de la función social por parte del productor mayor a un (1) año y de acuerdo a sus normas y procedimientos propios de la organización a la que pertenece'],
            ['tipo_persona_id' => '3', 'nombre' => 'Reasignación por sentencia', 'descripcion' => 'Reasignación por sentencia penal ejecutoriada, relacionada a delitos de sustancias controladas'],
            ['tipo_persona_id' => '3', 'nombre' => 'Otros', 'descripcion' => 'Otros'],
            ['tipo_persona_id' => '3', 'nombre' => 'Sucesión hereditaria', 'descripcion' => 'Sucesión hereditaria hasta el segundo grado consanguineo en linea ascendente o descendente'],
            ['tipo_persona_id' => '3', 'nombre' => 'Disposición del titular', 'descripcion' => 'Disposición del titular dentro de la comunidad, sindicato, federacion o confederación'],
            ['tipo_persona_id' => '3', 'nombre' => 'Inactividad', 'descripcion' => 'Inactividad en la comercialización de la hoja de coca durante doce (12) meses continuos sin justificación alguna, previo reporte anul del Viceministerio de Coca y Desarrollo Integral'],
            ['tipo_persona_id' => '3', 'nombre' => 'Suspención definitiva', 'descripcion' => 'Suspención definitiva del titular derivada de proceso administrativo'],
		];	foreach ($data as $data) {
			App\Sustitucion::create($data);
		}
    }
}
