<?php

use Illuminate\Database\Seeder;
use App\ZonaProductiva;

class ZonasProductivasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ZonaProductiva::truncate();
        ZonaProductiva::create([
            'codigo' => 'COD1',
            'nombre' => 'Originaria y Ancestral',
            'descripcion' => 'Zona Autorizada reconocida en La Paz',
            'color' => '#FF0000'
        ]);

        ZonaProductiva::create([
            'codigo' => 'COD2',
            'nombre' => 'Originaria y Ancestral con registro catastro',
            'descripcion' => 'Zona Autorizada reconocida en La Paz',
            'color' => '#FFD500'
        ]);

        ZonaProductiva::create([
            'codigo' => 'COD3',
            'nombre' => 'Con registro y catastro',
            'descripcion' => 'Zona Autorizada reconocida en La Paz',
            'color' => '#2A9300'
        ]);
    }
}
