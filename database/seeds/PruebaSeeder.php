<?php

use Illuminate\Database\Seeder;
use App\Comunidad;
use App\Localidad;
use App\Ruta;

class PruebaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Este seeder es para hacer pruebas en el sistema
        Comunidad::create([
            'municipio_id' => 1,
            'nombre' => 'Comunidad de prueba'
        ]);

        Localidad::create([
            'nombre' => 'Localidad de prueba'
        ]);

        $ruta = Ruta::create([
            'municipio_id' => 1,
            'codigo' => 'ASD',
            'nombre' => 'Ruta de prueba',
            'descripcion' => 'Esta ruta es de pruebas',
            'origen_id' => 1,
            'destino_id' => 3,
        ]);

        $ruta->puestos_de_control()->attach([1,2,3,4]);
    }
}
