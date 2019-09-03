<?php

use Illuminate\Database\Seeder;

class TiposInfraccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
            ['id'=>'1','nombre'=>'Leves'],
            ['id'=>'2','nombre'=>'Graves'],
            ['id'=>'3','nombre'=>'Gravísimas'],
        ]; foreach($data as $data){
            App\TipoInfraccion::create($data);
        }
    }
}
