<?php

use Illuminate\Database\Seeder;
use App\Departamento;
use App\Provincia;
use App\Municipio;
use App\Regional;
use App\Federacion;
use App\Central;

class AllSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departamento = Departamento::create([
            'codigo' => 'LP',
            'nombre' => 'La Paz',
            'sigla' => 'LP',
            'descripcion' => 'La Paz, Ciudad Maravilla'
        ]);
            // Originaria ancestral
            $provincia = Provincia::create([
                'departamento_id' => $departamento->id,
                'codigo' => 'NORY',
                'nombre' => 'Nor Yungas',
                'descripcion' => 'Provincia de La Paz'
            ]);
                $municipio = Municipio::create([
                    'provincia_id' => $provincia->id,
                    'codigo' => 'COROI',
                    'nombre' => 'Coroico',
                    'descripcion' => 'Municipio de La Paz'
                ]);
                    Federacion::create([
                        'municipio_id' => $municipio->id,
                        'codigo' => 'NO-CO',
                        'nombre' => 'Nor Yungas - Coroico',
                        'descripcion' => 'Federacion de La Paz'
                    ]);
                    Regional::create([
                        'municipio_id' => $municipio->id,
                        'nombre' => 'Coroico',
                        'descripcion' => 'Regional de La Paz'
                    ]);
                    Regional::create([
                        'municipio_id' => $municipio->id,
                        'nombre' => 'Suapi Quilo Quilo',
                        'descripcion' => 'Regional de La Paz'
                    ]);
                    Regional::create([
                        'municipio_id' => $municipio->id,
                        'nombre' => 'Cruz Loma Nueva Esperanza',
                        'descripcion' => 'Regional de La Paz'
                    ]);
                $municipio = Municipio::create([
                    'provincia_id' => $provincia->id,
                    'codigo' => 'CORIP',
                    'nombre' => 'Coripata',
                    'descripcion' => 'Municipio de La Paz'
                ]);
                    Federacion::create([
                        'municipio_id' => $municipio->id,
                        'codigo' => 'N-C',
                        'nombre' => 'Nor Yungas - Coripata',
                        'descripcion' => 'Federacion de La Paz'
                    ]);
                    Regional::create([
                        'municipio_id' => $municipio->id,
                        'nombre' => 'Coripata',
                        'descripcion' => 'Regional de La Paz'
                    ]);
                    Regional::create([
                        'municipio_id' => $municipio->id,
                        'nombre' => 'Milluguaya',
                        'descripcion' => 'Regional de La Paz'
                    ]);
                    Regional::create([
                        'municipio_id' => $municipio->id,
                        'nombre' => 'San Juan Tocoroni',
                        'descripcion' => 'Regional de La Paz'
                    ]);
                    Regional::create([
                        'municipio_id' => $municipio->id,
                        'nombre' => 'Trinidad Pampa',
                        'descripcion' => 'Regional de La Paz'
                    ]);
                    Federacion::create([
                        'municipio_id' => $municipio->id,
                        'codigo' => 'L-C-A',
                        'nombre' => 'La Concordia-Arapata',
                        'descripcion' => 'Federacion de La Paz'
                    ]);
                    Regional::create([
                        'municipio_id' => $municipio->id,
                        'nombre' => 'Arapata',
                        'descripcion' => 'Regional de La Paz'
                    ]);
            $provincia = Provincia::create([
                'departamento_id' => $departamento->id,
                'codigo' => 'SUDY',
                'nombre' => 'Sud Yungas',
                'descripcion' => 'Provincia de La Paz'
            ]);
                $municipio = Municipio::create([
                    'provincia_id' => $provincia->id,
                    'codigo' => 'CHULU',
                    'nombre' => 'Chulumani',
                    'descripcion' => 'Municipio de La Paz'
                ]);
                    Federacion::create([
                        'municipio_id' => $municipio->id,
                        'codigo' => 'S-CH',
                        'nombre' => 'Sud Yungas - Chulumani',
                        'descripcion' => 'Federacion de La Paz'
                    ]);
                    Regional::create([
                        'municipio_id' => $municipio->id,
                        'nombre' => 'Chulumani',
                        'descripcion' => 'Regional de La Paz'
                    ]);
                    Regional::create([
                        'municipio_id' => $municipio->id,
                        'nombre' => 'Huancané',
                        'descripcion' => 'Regional de La Paz'
                    ]);
                $municipio = Municipio::create([
                    'provincia_id' => $provincia->id,
                    'codigo' => 'IRUPA',
                    'nombre' => 'Irupana',
                    'descripcion' => 'Municipio de La Paz'
                ]);
                    Federacion::create([
                        'municipio_id' => $municipio->id,
                        'codigo' => 'S-IR',
                        'nombre' => 'Sud Yungas - Irupana',
                        'descripcion' => 'Federacion de La Paz'
                    ]);
                    Regional::create([
                        'municipio_id' => $municipio->id,
                        'nombre' => 'Irupana',
                        'descripcion' => 'Regional de La Paz'
                    ]);
                    Regional::create([
                        'municipio_id' => $municipio->id,
                        'nombre' => 'Chicaloma',
                        'descripcion' => 'Regional de La Paz'
                    ]);
                $municipio = Municipio::create([
                    'provincia_id' => $provincia->id,
                    'codigo' => 'YANAC',
                    'nombre' => 'Yanacachi',
                    'descripcion' => 'Municipio de La Paz'
                ]);
                    Federacion::create([
                        'municipio_id' => $municipio->id,
                        'codigo' => 'S-YAN',
                        'nombre' => 'Sud Yungas - Yanacachi',
                        'descripcion' => 'Federacion de La Paz'
                    ]);
                    Regional::create([
                        'municipio_id' => $municipio->id,
                        'nombre' => 'Yanacachi',
                        'descripcion' => 'Regional de La Paz'
                    ]);
                $municipio = Municipio::create([
                    'provincia_id' => $provincia->id,
                    'codigo' => 'ASUNT',
                    'nombre' => 'La Asunta',
                    'descripcion' => 'Municipio de La Paz'
                ]);
                    Federacion::create([
                        'municipio_id' => $municipio->id,
                        'codigo' => 'S-CHA',
                        'nombre' => 'Sud Yungas - Yanacachi',
                        'descripcion' => 'Federacion de La Paz'
                    ]);
                    Regional::create([
                        'municipio_id' => $municipio->id,
                        'nombre' => 'Chamaca',
                        'descripcion' => 'Regional de La Paz'
                    ]);
                    Federacion::create([
                        'municipio_id' => $municipio->id,
                        'codigo' => 'S-ASU',
                        'nombre' => 'Sud Yungas - La asunta',
                        'descripcion' => 'Federacion de La Paz'
                    ]);
                    Regional::create([
                        'municipio_id' => $municipio->id,
                        'nombre' => 'La Asunta',
                        'descripcion' => 'Regional de La Paz'
                    ]);
            $provincia = Provincia::create([
                'departamento_id' => $departamento->id,
                'codigo' => 'INQ',
                'nombre' => 'Inquisivi',
                'descripcion' => 'Provincia de La Paz'
            ]);
                Municipio::create([
                    'provincia_id' => $provincia->id,
                    'codigo' => 'CAJUA',
                    'nombre' => 'Cajuata',
                    'descripcion' => 'Municipio de La Paz'
                ]); 
                Municipio::create([
                    'provincia_id' => $provincia->id,
                    'codigo' => 'LICOM',
                    'nombre' => 'Licoma',
                    'descripcion' => 'Municipio de La Paz'
                ]);
                $municipio = Municipio::create([
                    'provincia_id' => $provincia->id,
                    'codigo' => 'INQUI',
                    'nombre' => 'Inquisivi',
                    'descripcion' => 'Municipio de La Paz'
                ]);
                    Federacion::create([
                        'municipio_id' => $municipio->id,
                        'codigo' => 'INQUI',
                        'nombre' => 'Inquisivi',
                        'descripcion' => 'Federacion de La Paz'
                    ]);
                    Regional::create([
                        'municipio_id' => $municipio->id,
                        'nombre' => 'Inquisivi',
                        'descripcion' => 'Regional de La Paz'
                    ]); 
            $provincia = Provincia::create([
                'departamento_id' => $departamento->id,
                'codigo' => 'PFT',
                'nombre' => 'Franz Tamayo',
                'descripcion' => 'Provincia de La Paz'
            ]);
                $municipio = Municipio::create([
                    'provincia_id' => $provincia->id,
                    'codigo' => 'APOLO',
                    'nombre' => 'Apolo',
                    'descripcion' => 'Municipio de La Paz'
                ]);
            $provincia = Provincia::create([
                'departamento_id' => $departamento->id,
                'codigo' => 'PDM',
                'nombre' => 'Pedro Domingo Murillo',
                'descripcion' => 'Provincia de La Paz'
            ]);
                $municipio = Municipio::create([
                    'provincia_id' => $provincia->id,
                    'codigo' => 'NSL',
                    'nombre' => 'Nuestra Señora de La Paz',
                    'descripcion' => 'Municipio de La Paz'
                ]);
            $provincia = Provincia::create([
                'departamento_id' => $departamento->id,
                'codigo' => 'IDM',
                'nombre' => 'Idelfonso de la muñecas',
                'descripcion' => 'Provincia de La Paz'
            ]);
                $municipio = Municipio::create([
                    'provincia_id' => $provincia->id,
                    'codigo' => 'AYA',
                    'nombre' => 'Ayata',
                    'descripcion' => 'Municipio de La Paz'
                ]);
                $municipio = Municipio::create([
                    'provincia_id' => $provincia->id,
                    'codigo' => 'AUC',
                    'nombre' => 'Aucapata',
                    'descripcion' => 'Municipio de La Paz'
                ]);
            $provincia = Provincia::create([
                'departamento_id' => $departamento->id,
                'codigo' => 'CARAN',
                'nombre' => 'Caranavi',
                'descripcion' => 'Provincia de La Paz'
            ]);
                $municipio = Municipio::create([
                    'provincia_id' => $provincia->id,
                    'codigo' => 'CARAN',
                    'nombre' => 'Caranavi',
                    'descripcion' => 'Municipio de La Paz'
                ]);
                $municipio = Municipio::create([
                    'provincia_id' => $provincia->id,
                    'codigo' => 'AB',
                    'nombre' => 'Alto Beni',
                    'descripcion' => 'Municipio de La Paz'
                ]);
            $provincia = Provincia::create([
                'departamento_id' => $departamento->id,
                'codigo' => 'BAUSA',
                'nombre' => 'Bautista Saavedra',
                'descripcion' => 'Provincia de La Paz'
            ]);
                $municipio = Municipio::create([
                    'provincia_id' => $provincia->id,
                    'codigo' => 'CHARA',
                    'nombre' => 'Charazani',
                    'descripcion' => 'Municipio de La Paz'
                ]);
        $departamento = Departamento::create([
            'codigo' => 'CB',
            'nombre' => 'Cochabamba',
            'sigla' => 'CB',
            'descripcion' => 'Ciudad de Bolivia'
        ]);
            $provincia = Provincia::create([
                'departamento_id' => $departamento->id,
                'codigo' => 'CHAP',
                'nombre' => 'Chapare',
                'descripcion' => 'Provincia de Cochabamba'
            ]);
                $municipio = Municipio::create([
                    'provincia_id' => $provincia->id,
                    'codigo' => 'VTUN',
                    'nombre' => 'Villa Tunari',
                    'descripcion' => 'Municipio de Cochabamba'
                ]);
        Departamento::create([
            'codigo' => 'SC',
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
            'codigo' => 'PD',
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
