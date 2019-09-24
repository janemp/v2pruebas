<?php

use Illuminate\Database\Seeder;
use App\MotivosActualizacion;

class MotivosActualizacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [            
            ['id'=>'1','nombre' => 'Ataque de plagas o enfermedades.'],
            ['id'=>'2','nombre' => 'Pérdida total o parcial por desastres naturales.'],
            ['id'=>'3','nombre' => 'Disminución del rendimiento del cultivo.'],
            ['id'=>'4','nombre' => 'Afectación del Predio agrícola por construcción.'],
            ['id'=>'5','nombre' => 'Afectación por ampliación del área urbana.'],
            ['id'=>'6','nombre' => 'Desplazamiento de parcelas de coca catastradas.'],            
        ];	
        foreach ($data as $data) {
			App\MotivosActualizacion::create($data);
		}
    }
}
