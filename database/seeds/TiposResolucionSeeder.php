<?php

use Illuminate\Database\Seeder;

class TiposResolucionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => '1', 'nombre' => 'Sustitución de Registro de Productores'],
			['id' => '2', 'nombre' => 'Cesación temporal de registro de comercialización'],
            ['id' => '3', 'nombre' => 'Renovaciones'],
            ['id' => '4', 'nombre' => 'Decomisos'],
            ['id' => '5', 'nombre' => 'Devoluciones'],
            ['id' => '6', 'nombre' => 'Donaciones'],
            ['id' => '7', 'nombre' => 'Incineraciones'],
            /*['id' => '8', 'nombre' => 'Investigación, industrialización y/o producción de abono'],*/  
		];	foreach ($data as $data) {
			App\TipoResolucion::create($data);
		}
    }
}
