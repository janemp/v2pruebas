<?php

use Illuminate\Database\Seeder;

class PaisesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Pais::truncate();
        $paises = App\Pais::create([
            'nombre' => 'Bolivia',
        ]);
    }
}
