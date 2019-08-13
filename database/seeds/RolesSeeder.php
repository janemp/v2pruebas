<?php

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
			['name' => 'supervisor', 'display_name' => 'supervisor', 'description' => 'supervisor'],
            ['name' => 'digcoin', 'display_name' => 'digcoin', 'description' => 'digcoin'],
            ['name' => 'digprococa', 'display_name' => 'digprococa', 'description' => 'digprococa'],
            ['name' => 'verificador', 'display_name' => 'verificador', 'description' => 'verificador'],
            ['name' => 'juridica', 'display_name' => 'juridica', 'description' => 'juridica'],
		];	foreach ($data as $data) {
			App\Role::create($data);
		}
    }
}
