<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission_listar = App\Permission::create([
            'name' => 'listar',
            'display_name' => 'listar',
            'description' => 'listar',
        ]);
        $permission_registrar = App\Permission::create([
            'name' => 'registrar',
            'display_name' => 'registrar',
            'description' => 'registrar',
        ]);
        $permission_editar = App\Permission::create([
            'name' => 'editar',
            'display_name' => 'editar',
            'description' => 'editar',
        ]);
        $permission_eliminar = App\Permission::create([
            'name' => 'eliminar',
            'display_name' => 'eliminar',
            'description' => 'eliminar',
        ]);
        $permission_imprimir = App\Permission::create([
            'name' => 'imprimir',
            'display_name' => 'imprimir',
            'description' => 'imprimir',
        ]);
        // roles
        $role_admin = App\Role::create([
            'name' => 'admin',
            'display_name' => 'ADMIN',
            'description' => 'ADMINISTRADOR',
        ]);
        $role_digprococa = App\Role::create([
            'name' => 'digprococa',
            'display_name' => 'DIGPROCOCA',
            'description' => 'DIRECCIÓN GENERAL DE DESARROLLO INTEGRAL DE LAS REGIONES PRODUCTORAS DE COCA',
        ]);
        $role_digcoin = App\Role::create([
            'name' => 'digcoin',
            'display_name' => 'DIGCOIN',
            'description' => 'DIRECCIÓN GENERAL DE COMERCIALILZACIÓN E INDUSTRIALIZACIÓN DE LA HOJA DE COCA',
        ]);
        $role_fonadin = App\Role::create([
            'name' => 'fonadin',
            'display_name' => 'FONADIN',
            'description' => 'FONDO NACIONAL DE DESARROLLO INTEGRAL',
        ]);
        $role_oii = App\Role::create([
            'name' => 'oii',
            'display_name' => 'OII',
            'description' => 'OBRAS DE IMPACTO INMEDIATO',
        ]);
        $role_productor = App\Role::create([
            'name' => 'productor',
            'display_name' => 'PRODUCTOR',
            'description' => 'PRODUCTOR',
        ]);
        $role_comercializador = App\Role::create([
            'name' => 'comercializador',
            'display_name' => 'COMERCIALIZADOR',
            'description' => 'COMERCIALIZADOR',
        ]);
        $role_oii = App\Role::create([
            'name' => 'vcdi',
            'display_name' => 'VCDI',
            'description' => 'VCDI',
        ]);

        // usuarios
        $user_admin = App\User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ]);        
        $user_admin->attachRole($role_admin);
        $role_admin->attachPermission($permission_listar);
        $role_admin->attachPermission($permission_registrar);
        $role_admin->attachPermission($permission_editar);
        $role_admin->attachPermission($permission_eliminar);
        $role_admin->attachPermission($permission_imprimir);

        $user_digprococa = App\User::create([
            'name' => 'digprococa',
            'email' => 'digprococa@admin.com',
            'password' => bcrypt('digprococa'),
        ]);        
        $user_digprococa->attachRole($role_digprococa);
        $role_digprococa->attachPermission($permission_listar);
        $role_digprococa->attachPermission($permission_registrar);
        $role_digprococa->attachPermission($permission_editar);
        $role_digprococa->attachPermission($permission_imprimir);

        $user_digcoin = App\User::create([
            'name' => 'digcoin',
            'email' => 'digcoin@admin.com',
            'password' => bcrypt('digcoin'),
        ]);        
        $user_digcoin->attachRole($role_digcoin);
        $role_digcoin->attachPermission($permission_listar);
        $role_digcoin->attachPermission($permission_registrar);
        $role_digcoin->attachPermission($permission_editar);
        $role_digcoin->attachPermission($permission_imprimir);

        $user_fonadin = App\User::create([
            'name' => 'fonadin',
            'email' => 'fonadin@admin.com',
            'password' => bcrypt('fonadin'),
        ]);        
        $user_fonadin->attachRole($role_fonadin);
        $role_fonadin->attachPermission($permission_listar);
        $role_fonadin->attachPermission($permission_registrar);
        $role_fonadin->attachPermission($permission_editar);
        $role_fonadin->attachPermission($permission_imprimir);

        $user_oii = App\User::create([
            'name' => 'oii',
            'email' => 'oii@admin.com',
            'password' => bcrypt('oii'),
        ]);        
        $user_oii->attachRole($role_oii);
        $role_oii->attachPermission($permission_listar);
        $role_oii->attachPermission($permission_registrar);
        $role_oii->attachPermission($permission_editar);
        $role_oii->attachPermission($permission_imprimir);

    }
}
