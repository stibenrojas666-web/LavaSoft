<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SeguridadSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = config('seguridad.guard');

        $permisoAdmin = Permission::firstOrCreate(
            ['name' => config('seguridad.permiso_admin'), 'guard_name' => $guard],
            ['modulo' => 'seguridad']
        );

        if ($permisoAdmin->modulo === null) {
            $permisoAdmin->update(['modulo' => 'seguridad']);
        }

        foreach (config('seguridad.modulos', []) as $modulo) {
            $modulo = mb_strtolower($modulo);

            foreach (config('seguridad.acciones', []) as $accion) {
                Permission::firstOrCreate(
                    ['name' => $accion . ' ' . $modulo, 'guard_name' => $guard],
                    ['modulo' => $modulo]
                );
            }
        }

        $rolAdmin = Role::firstOrCreate(
            ['name' => config('seguridad.rol_admin'), 'guard_name' => $guard],
            ['activo' => true]
        );
        $rolAdmin->update(['activo' => true]);
        $rolAdmin->syncPermissions(Permission::where('guard_name', $guard)->get());

        $modelo = config('auth.providers.users.model');
        $datos = config('seguridad.admin');

        $admin = $modelo::firstOrCreate(
            ['email' => $datos['email']],
            ['name' => $datos['name'], 'password' => Hash::make($datos['password'])]
        );
        $admin->assignRole($rolAdmin);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
