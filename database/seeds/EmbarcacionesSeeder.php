<?php

use Illuminate\Database\Seeder;

class EmbarcacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [            
            ['nombre' => 'Balsa'],
            ['nombre' => 'Embarcación'],
            ['nombre' => 'Canoa'],
            ['nombre' => 'Bote'],
            ['nombre' => 'Drakkar'],
            ['nombre' => 'Galera'],
            ['nombre' => 'Junco'],
            ['nombre' => 'Carabela'],
            ['nombre' => 'MoticGaleónicleta'],
            ['nombre' => 'Fragata'],
            ['nombre' => 'Urca'],
            ['nombre' => 'Buque'],
            ['nombre' => 'Moto de agua'],
            ['nombre' => 'Transbordador'],
            ['nombre' => 'Catamarán'],
            ['nombre' => 'Transatlántico'],
            ['nombre' => 'Rompehielos   '],
            ['nombre' => 'Yate'],
            ['nombre' => 'Otros'],
        ];	
        foreach ($data as $data) {
			App\Embarcacion::create($data);
		}
    }
}
