<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $roles = DB::table('Roles')->get(['Id', 'Nombre']);

        $adminRole = $roles->first(function ($role) {
            return in_array(mb_strtolower(trim((string) $role->Nombre)), ['admin', 'administrador'], true);
        });

        $userRole = $roles->first(function ($role) {
            return in_array(mb_strtolower(trim((string) $role->Nombre)), ['usuario', 'user', 'cliente'], true);
        });

        if ($adminRole) {
            DB::table('Roles')->where('Id', $adminRole->Id)->update([
                'Nombre' => 'admin',
            ]);
        } else {
            $adminRoleId = DB::table('Roles')->insertGetId([
                'Nombre' => 'admin',
            ]);

            $adminRole = (object) ['Id' => $adminRoleId, 'Nombre' => 'admin'];
        }

        if ($userRole) {
            DB::table('Roles')->where('Id', $userRole->Id)->update([
                'Nombre' => 'Usuario',
            ]);
        } else {
            $userRoleId = DB::table('Roles')->insertGetId([
                'Nombre' => 'Usuario',
            ]);

            $userRole = (object) ['Id' => $userRoleId, 'Nombre' => 'Usuario'];
        }

        $canonicalIds = [(int) $adminRole->Id, (int) $userRole->Id];

        DB::table('UsuarioRoles')
            ->whereNotIn('RolId', $canonicalIds)
            ->update(['RolId' => $userRole->Id]);

        DB::table('Roles')
            ->whereNotIn('Id', $canonicalIds)
            ->delete();
    }

    public function down(): void
    {
        DB::table('Roles')
            ->whereRaw('LOWER(Nombre) = ?', ['usuario'])
            ->update(['Nombre' => 'Cliente']);
    }
};
