<?php

use Illuminate\Database\Seeder;

class MotivosIncineracionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [            
            ['nombre' => 'Se encuentra en proceso de descomposición.'],
            ['nombre' => 'No fue utilizado para su revalorización, donación, investigación, y/o producción de abono.'],
            ['nombre' => 'Incautado.'],
            ['nombre' => 'Por disposición judicial.'],
            ['nombre' => 'Otros.']            
        ];	
        foreach ($data as $data) {
			App\MotivoIncineracion::create($data);
		}
    }
}
