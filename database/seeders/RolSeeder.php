<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $roles = [
            'super-admin' => Permission::pluck('name')->all(),
            'admin-eventos' => [
                'dashboard.view',
                'catalog.nationalities.manage',
                'catalog.modalities.manage',
                'catalog.locations.manage',
                'catalog.profile-types.manage',
                'catalog.conference-types.manage',
                'people.manage',
                'speakers.manage',
                'events.manage',
                'conferences.manage',
                'attendances.manage',
                'diplomas.manage',
                'reports.events.view',
            ],
            'gestor-contenido' => [
                'dashboard.view',
                'speakers.manage',
                'events.manage',
                'conferences.manage',
                'diplomas.manage',
                'reports.events.view',
            ],
            'participante' => [
                'participant.portal.access',
                'participant.registrations.manage',
                'participant.history.view',
                'participant.diplomas.view',
                'diplomas.validate',
            ],
            'conferencista' => [
                'participant.portal.access',
                'participant.registrations.manage',
                'participant.history.view',
                'participant.diplomas.view',
            ],
            'staff-evento' => [
                'dashboard.view',
                'participant.portal.access',
                'participant.registrations.manage',
                'participant.history.view',
                'participant.diplomas.view',
                'staff.portal.access',
                'staff.attendance.scan',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::findOrCreate($roleName, 'web');
            $role->syncPermissions($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
