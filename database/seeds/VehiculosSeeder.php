<?php

use Illuminate\Database\Seeder;

class VehiculosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [            
            ['nombre' => 'Automovil'],
            ['nombre' => 'Peta'],
            ['nombre' => 'Vagoneta'],
            ['nombre' => 'Camioneta'],
            ['nombre' => 'Minibus'],
            ['nombre' => 'Bus'],
            ['nombre' => 'Camión'],
            ['nombre' => 'Trailer'],
            ['nombre' => 'Moticicleta'],
            ['nombre' => 'Otros'],
            
        ];	
        foreach ($data as $data) {
			App\Vehiculo::create($data);
		}
    }
}
