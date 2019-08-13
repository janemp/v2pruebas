<?php

use Illuminate\Database\Seeder;
use App\Mercado;

class MercadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Mercado::create([
            'departamento_id' => 1,
            'codigo' => 'ADEPC',
            'nombre' => 'Mercado de la ADEPCOCA',
            'descripcion' => 'Unico mercado reconocido en la ciudad de La Paz',
            'latitud' => -16.473639,
            'longitud' => -68.118206,
            'direccion' => 'Av. Ramiro Castillo, La Paz'
        ]);

        Mercado::create([
            'departamento_id' => 2,
            'codigo' => 'SACAB',
            'nombre' => 'Mercado de Sacaba',
            'descripcion' => 'Unico mercado reconocido en la ciudad de Cochabamba',
            'latitud' => -17.407833,
            'longitud' => -66.038190,
            'direccion' => 'Sacaba'
        ]);
    }
}
