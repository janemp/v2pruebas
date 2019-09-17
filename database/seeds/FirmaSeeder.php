<?php

use Illuminate\Database\Seeder;
use App\Firma;

class FirmaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['nombre' => 'EULOGIO CONDORI MAMANI', 'cargo' => 'VICEMINISTRO', 'img' => '/uploads/carnets/carnet_viceministro.png', 'institucion' => 'VICEMINISTERIO DE COCA Y DESARROLLO INTEGRAL'],
            ['nombre' => 'MARTIN CERRUDO CALATAYUD', 'cargo' => 'DIRECTOR GENERAL', 'img' => '/uploads/carnets/carnet_viceministro.png', 'institucion' => 'DIGCOIN'],
            ['nombre' => 'Sr. Jose Luis Saavedra Gonzales', 'cargo' => 'Jefe de comercialización', 'img' => '/uploads/carnets/carnet_viceministro.png']
		];	foreach ($data as $data) {
			Firma::create($data);
		}     
    }
}
