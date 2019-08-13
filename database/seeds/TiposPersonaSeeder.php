<?php

use Illuminate\Database\Seeder;

class TiposPersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
			['id' => '1', 'nombre' => 'Productor'],
            ['id' => '2', 'nombre' => 'Productor al detalle'],
            ['id' => '3', 'nombre' => 'Comercializador al detalle'],
            ['id' => '4', 'nombre' => 'Empresa de industrialización'],
            ['id' => '5', 'nombre' => 'Entidad de investigación'],
            ['id' => '6', 'nombre' => 'Representante y organización beneficiario de donaciones'],
            ['id' => '7', 'nombre' => 'Beneficiarios para desarrollo integraI'],
		];	foreach ($data as $data) {
			App\TipoPersona::create($data);
		}
    }
}
