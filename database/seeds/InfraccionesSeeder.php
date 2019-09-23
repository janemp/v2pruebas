<?php

use Illuminate\Database\Seeder;

class InfraccionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
            ['id'=>'1','codigo'=>'IN001','nombre'=>'Leves','descripcion'=>'Leves','severidad'=>'Leve','bloqueante'=>false,'estado'=>'1'],
            ['id'=>'2','codigo'=>'IN002','nombre'=>'Graves','descripcion'=>'Graves','severidad'=>'Grave','bloqueante'=>false,'estado'=>'1'],
            ['id'=>'3','codigo'=>'IN003','nombre'=>'Gravísimas','descripcion'=>'Gravísimas','severidad'=>'Gravisíma','bloqueante'=>false,'estado'=>'1'],
        ]; foreach($data as $data){
            App\Infraccion::create($data);
        }
    }
}
