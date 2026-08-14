<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',
            'catalog.nationalities.manage',
            'catalog.modalities.manage',
            'catalog.locations.manage',
            'catalog.profile-types.manage',
            'catalog.conference-types.manage',
            'catalog.currencies.manage',
            'people.manage',
            'speakers.manage',
            'events.manage',
            'conferences.manage',
            'attendances.manage',
            'diplomas.manage',
            'reports.events.view',
            'security.roles.manage',
            'security.users.manage',
            'participant.portal.access',
            'participant.registrations.manage',
            'participant.history.view',
            'participant.diplomas.view',
            'staff.portal.access',
            'staff.attendance.scan',
            'diplomas.validate',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
