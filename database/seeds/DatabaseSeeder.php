<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(PaisesSeeder::class);
        $this->call(ZonasAutorizadasSeeder::class);
        $this->call(AllSeeder::class);
        $this->call(MercadosSeeder::class);
        $this->call(TiposPersonaSeeder::class);
        $this->call(SustitucionesSeeder::class);
        $this->call(CesacionesSeeder::class);
        $this->call(VehiculosSeeder::class);
        $this->call(MarcasSeeder::class);
        $this->call(EmbarcacionesSeeder::class);
        $this->call(CausasRetencionSeeder::class);
        $this->call(PuestosSeeder::class);
        $this->call(MotivosIncineracionSeeder::class);
        $this->call(TiposResolucionSeeder::class);
        // $this->call(RolesSeeder::class);
        // $this->call(PermisosSeeder::class);
        // $this->call(LaratrustSeeder::class);        
    }
}
