<?php

use Illuminate\Database\Seeder;
use App\ZonaAutorizada;

class ZonasAutorizadasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ZonaAutorizada::truncate();
        ZonaAutorizada::create([
            'codigo' => 'COD1',
            'nombre' => 'Originaria y Ancestral',
            'descripcion' => 'Zona Autorizada reconocida en La Paz',
            'color' => '#FF0000'
        ]);

        ZonaAutorizada::create([
            'codigo' => 'COD2',
            'nombre' => 'Originaria y Ancestral con registro catastro',
            'descripcion' => 'Zona Autorizada reconocida en La Paz',
            'color' => '#FFD500'
        ]);

        ZonaAutorizada::create([
            'codigo' => 'COD3',
            'nombre' => 'Con registro y catastro',
            'descripcion' => 'Zona Autorizada reconocida en La Paz',
            'color' => '#2A9300'
        ]);
    }
}
