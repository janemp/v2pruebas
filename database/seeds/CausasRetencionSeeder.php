<?php

use Illuminate\Database\Seeder;

class CausasRetencionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [            
            ['nombre' => 'Transportar en las rutas autorizadas la hoja de coca en su estado natural, sin portar la respectiva documentación.'],
            ['nombre' => 'Transportar la hoja de coca en su estado natural fuera de las rutas y puestos de control establecidos, considerado desvío.'],
            ['nombre' => 'Comercializar la hoja de coca en su estado natural, en lugares diferentes a los mercados o puestos de venta, autorizados.'],
            ['nombre' => 'Extender o faltar el peso del taque en mas de tres (3) libras.'],            
        ];	
        foreach ($data as $data) {
			App\CausaRetencion::create($data);
		}
    }
}
