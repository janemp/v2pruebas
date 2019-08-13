<?php

use Illuminate\Database\Seeder;
use App\Departamento;

class DepartamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Departamento::create([
            'codigo' => 'LP',
            'nombre' => 'La Paz',
            'sigla' => 'LP',
            'descripcion' => 'La Paz, Ciudad Maravilla'
        ]);

        Departamento::create([
            'codigo' => 'CBA',
            'nombre' => 'Cochabamba',
            'sigla' => 'CB',
            'descripcion' => 'Ciudad de Bolivia'
        ]);
        
        Departamento::create([
            'codigo' => 'SCZ',
            'nombre' => 'Santa Cruz',
            'sigla' => 'SC',
            'descripcion' => 'Ciudad de Bolivia'
        ]);

        Departamento::create([
            'codigo' => 'OR',
            'nombre' => 'Oruro',
            'sigla' => 'OR',
            'descripcion' => 'Ciudad de Bolivia'
        ]);

        Departamento::create([
            'codigo' => 'TJ',
            'nombre' => 'Tarija',
            'sigla' => 'TJ',
            'descripcion' => 'Ciudad de Bolivia'
        ]);

        Departamento::create([
            'codigo' => 'CH',
            'nombre' => 'Chuquisaca',
            'sigla' => 'CH',
            'descripcion' => 'Ciudad de Bolivia'
        ]);

        Departamento::create([
            'codigo' => 'PT',
            'nombre' => 'Potosí',
            'sigla' => 'PT',
            'descripcion' => 'Ciudad de Bolivia'
        ]);

        Departamento::create([
            'codigo' => 'PA',
            'nombre' => 'Pando',
            'sigla' => 'PD',
            'descripcion' => 'Ciudad de Bolivia'
        ]);

        Departamento::create([
            'codigo' => 'BN',
            'nombre' => 'Beni',
            'sigla' => 'BN',
            'descripcion' => 'Ciudad de Bolivia'
        ]);
    }
}
