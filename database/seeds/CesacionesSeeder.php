<?php

use Illuminate\Database\Seeder;

class CesacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [            
            ['tipo_persona_id' => '2', 'nombre' => 'Por enfermedad', 'descripcion' => 'Por enfermedad, debidamente acreditada con certificado médico, hasta un maximo de cinco (5) meses, que podrá ser ampliado por un periodo similar por unica vez'],
            ['tipo_persona_id' => '2', 'nombre' => 'Por estado de gestacion', 'descripcion' => 'Por estado de gestación, debidamente acreditado con certificado médico.'],
            ['tipo_persona_id' => '2', 'nombre' => 'Por designación como servidor público', 'descripcion' => 'Por designación como servidor público o persona contratada por el Estado, hasta concluida su relación laboral, contractual o duración de su mandato'],
            ['tipo_persona_id' => '2', 'nombre' => 'Otros', 'descripcion' => 'Otros'],
            
            ['tipo_persona_id' => '3', 'nombre' => 'Por enfermedad', 'descripcion' => 'Por enfermedad, debidamente acreditada con certificado médico, hasta un maximo de cinco (5) meses, que podrá ser ampliado por un periodo similar por unica vez'],
            ['tipo_persona_id' => '3', 'nombre' => 'Por estado de gestacion', 'descripcion' => 'Por estado de gestación, debidamente acreditado con certificado médico.'],
            ['tipo_persona_id' => '3', 'nombre' => 'Por designación como servidor público', 'descripcion' => 'Por designación como servidor público o persona contratada por el Estado, hasta concluida su relación laboral, contractual o duración de su mandato'],
            ['tipo_persona_id' => '3', 'nombre' => 'Otros', 'descripcion' => 'Otros'],
            
		];	foreach ($data as $data) {
			App\Cesacion::create($data);
		}
    }
}
