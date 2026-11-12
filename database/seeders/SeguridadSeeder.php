<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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

        DB::transaction(function () use ($guard): void {

            // Crear los permisos generales de LavaSoft.
            $permisoAdmin = Permission::firstOrCreate(
                [
                    'name' => config('seguridad.permiso_admin'),
                    'guard_name' => $guard,
                ],
                ['modulo' => 'seguridad']
            );

            if ($permisoAdmin->modulo === null) {
                $permisoAdmin->update(['modulo' => 'seguridad']);
            }

            foreach (config('seguridad.modulos', []) as $modulo) {
                $modulo = mb_strtolower($modulo);

                foreach (config('seguridad.acciones', []) as $accion) {
                    Permission::firstOrCreate(
                        [
                            'name' => $accion . ' ' . $modulo,
                            'guard_name' => $guard,
                        ],
                        ['modulo' => $modulo]
                    );
                }
            }

            // Crear o recuperar el administrador.
            $rolAdmin = Role::firstOrCreate(
                [
                    'name' => config('seguridad.rol_admin'),
                    'guard_name' => $guard,
                ],
                ['activo' => true]
            );

            $rolAdmin->update(['activo' => true]);

            $rolAdmin->syncPermissions(
                Permission::where('guard_name', $guard)->get()
            );

            // Recuperar los roles anterior y nuevo.
            $nombreAntiguo = 'subadministrador';
            $nombreNuevo = config('seguridad.rol_recepcionista');

            $rolAntiguo = Role::where('name', $nombreAntiguo)
                ->where('guard_name', $guard)
                ->first();

            $rolRecepcionista = Role::where('name', $nombreNuevo)
                ->where('guard_name', $guard)
                ->first();

            // Si solo existe el rol antiguo, renombrarlo.
            // Así se conservan sus asignaciones de usuarios.
            if ($rolRecepcionista === null && $rolAntiguo !== null) {
                $rolAntiguo->update(['name' => $nombreNuevo]);
                $rolRecepcionista = $rolAntiguo;
            }

            // Si no existe ninguno, crear el nuevo rol.
            if ($rolRecepcionista === null) {
                $rolRecepcionista = Role::create([
                    'name' => $nombreNuevo,
                    'guard_name' => $guard,
                    'activo' => true,
                ]);
            }

            // Si existen ambos, trasladar los usuarios
            // del rol antiguo al nuevo antes de eliminarlo.
            if (
                $rolAntiguo !== null
                && $rolAntiguo->id !== $rolRecepcionista->id
            ) {
                foreach ($rolAntiguo->users()->get() as $usuario) {
                    $usuario->assignRole($rolRecepcionista);
                }

                $rolAntiguo->delete();
            }

            // Sincronizar siempre los permisos del recepcionista.
            $permisosRecepcionista = collect(
                config('seguridad.modulos_recepcionista', [])
            )
                ->flatMap(fn ($modulo) => collect(
                    config('seguridad.acciones_recepcionista', [])
                )->map(
                    fn ($accion) => $accion . ' ' . mb_strtolower($modulo)
                ))
                ->unique()
                ->values()
                ->all();

            $rolRecepcionista->syncPermissions(
                Permission::where('guard_name', $guard)
                    ->whereIn('name', $permisosRecepcionista)
                    ->get()
            );
        });

        // Buscar el administrador sin modificar su contraseña.
$modelo = config('auth.providers.users.model');
$datos = config('seguridad.admin');

$admin = $modelo::where('email', $datos['email'])->first();

if ($admin === null) {
    if (empty($datos['password'])) {
        throw new \RuntimeException(
            'No se puede crear el administrador: configura SEGURIDAD_ADMIN_PASSWORD en el archivo .env.'
        );
    }

    $admin = $modelo::create([
        'name' => $datos['name'],
        'email' => $datos['email'],
        'password' => Hash::make($datos['password']),
    ]);
}

$admin->assignRole(
    Role::where('name', config('seguridad.rol_admin'))
        ->where('guard_name', $guard)
        ->firstOrFail()
);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}