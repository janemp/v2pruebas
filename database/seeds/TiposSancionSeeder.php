<?php

use Illuminate\Database\Seeder;

class TiposSancionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
            ['id'=>'1','nombre'=>'Multa pecuniaria'],
            ['id'=>'2','nombre'=>'Decomiso'],
            ['id'=>'3','nombre'=>'Suspensión temporal'],
            ['id'=>'4','nombre'=>'Suspensión definitiva'],
        ]; foreach ($data as $data){
            App\TipoSancion::create($data);
        }
    }
}
