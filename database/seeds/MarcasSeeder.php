<?php

use Illuminate\Database\Seeder;

class MarcasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [            
            ['nombre' => 'Abarth'],
            ['nombre' => 'Alfa Romeo'],
            ['nombre' => 'Aston Martin'],
            ['nombre' => 'Audi'],
            ['nombre' => 'Bentley'],
            ['nombre' => 'BMW'],
            ['nombre' => 'Cadillac'],
            ['nombre' => 'Caterham'],
            ['nombre' => 'Chevrolet'],
            ['nombre' => 'Citroen'],
            ['nombre' => 'Dacia'],
            ['nombre' => 'Ferrari'],
            ['nombre' => 'Fiat'],
            ['nombre' => 'Ford'],
            ['nombre' => 'Honda'],
            ['nombre' => 'Infiniti'],
            ['nombre' => 'Isuzu'],
            ['nombre' => 'Iveco'],
            ['nombre' => 'Jaguar'],
            ['nombre' => 'Jeep'],
            ['nombre' => 'Kia'],
            ['nombre' => 'KTM'],
            ['nombre' => 'Lada'],
            ['nombre' => 'Lamborghini'],
            ['nombre' => 'Lancia'],
            ['nombre' => 'Land Rover'],
            ['nombre' => 'Lexus'],
            ['nombre' => 'Lotus'],
            ['nombre' => 'Maserati'],
            ['nombre' => 'Mazda'],
            ['nombre' => 'Mercedes-Benz'],
            ['nombre' => 'Mini'],
            ['nombre' => 'Mitsubishi'],
            ['nombre' => 'Morgan'],
            ['nombre' => 'Nissan'],
            ['nombre' => 'Opel'],
            ['nombre' => 'Peugeot'],
            ['nombre' => 'Piaggio'],
            ['nombre' => 'Porsche'],
            ['nombre' => 'Renault'],
            ['nombre' => 'Rolls-Royce'],
            ['nombre' => 'Seat'],
            ['nombre' => 'Skoda'],
            ['nombre' => 'Smart'],
            ['nombre' => 'SsangYong'],
            ['nombre' => 'Subaru'],
            ['nombre' => 'Suzuki'],
            ['nombre' => 'Tata'],
            ['nombre' => 'Tesla'],
            ['nombre' => 'Toyota'],
            ['nombre' => 'Volkswagen'],
            ['nombre' => 'Volvo'],
            ['nombre' => 'Ram'],
            ['nombre' => 'Otros'],	
		];	foreach ($data as $data) {
			App\Marca::create($data);
		}
    }
}
