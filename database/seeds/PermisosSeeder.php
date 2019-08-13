<?php

use Illuminate\Database\Seeder;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
			['name' => 'listar', 'display_name' => 'listar', 'description' => 'listar'],
            ['name' => 'registrar', 'display_name' => 'registrar', 'description' => 'registrar'],
            ['name' => 'editar', 'display_name' => 'editar', 'description' => 'editar'],
            ['name' => 'eliminar', 'display_name' => 'eliminar', 'description' => 'eliminar'],
            ['name' => 'imprimir', 'display_name' => 'imprimir', 'description' => 'imprimir'],
		];	foreach ($data as $data) {
			App\Permission::create($data);
		}
    }
}
