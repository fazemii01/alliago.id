<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Permissions are grouped by admin-dashboard section.
     * Format: 'permission_name' => 'Human-readable label'
     *
     * These ONLY control access inside the Filament admin dashboard.
     * Public-facing pages (landing page, visa browsing, etc.) are not affected.
     */
    public static array $groups = [
        'Operations' => [
            'applications.view_any'  => 'View Application List',
            'applications.view'      => 'View Application Detail',
            'applications.update'    => 'Process / Update Application',
            'applications.delete'    => 'Delete Application',
            'users.view_any'         => 'View User List',
            'users.view'             => 'View User Detail',
            'users.create'           => 'Create User',
            'users.update'           => 'Edit User',
            'users.delete'           => 'Delete User',
        ],
        'Finance' => [
            'invoices.view_any'      => 'View Invoice List',
            'invoices.view'          => 'View Invoice Detail',
            'payment_methods.view_any'  => 'View Payment Methods',
            'payment_methods.create'    => 'Create Payment Method',
            'payment_methods.update'    => 'Edit Payment Method',
            'payment_methods.delete'    => 'Delete Payment Method',
        ],
        'Catalog' => [
            'visa_products.view_any' => 'View Visa Product List',
            'visa_products.view'     => 'View Visa Product Detail',
            'visa_products.create'   => 'Create Visa Product',
            'visa_products.update'   => 'Edit Visa Product',
            'visa_products.delete'   => 'Delete Visa Product',
            'countries.view_any'     => 'View Country List',
            'countries.create'       => 'Create Country',
            'countries.update'       => 'Edit Country',
            'countries.delete'       => 'Delete Country',
        ],
        'Settings' => [
            'flight_pricing_config.view_any' => 'View Flight Pricing Config',
            'flight_pricing_config.update'   => 'Edit Flight Pricing Config',
            'site_faqs.view_any'     => 'View FAQ List',
            'site_faqs.create'       => 'Create FAQ',
            'site_faqs.update'       => 'Edit FAQ',
            'site_faqs.delete'       => 'Delete FAQ',
            'testimonials.view_any'  => 'View Testimonial List',
            'testimonials.create'    => 'Create Testimonial',
            'testimonials.update'    => 'Edit Testimonial',
            'testimonials.delete'    => 'Delete Testimonial',
            'roles.view_any'         => 'View Role List',
            'roles.create'           => 'Create Role',
            'roles.update'           => 'Edit Role & Assign Permissions',
            'roles.delete'           => 'Delete Role',
        ],
    ];

    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create all permissions (guard: web)
        foreach (self::$groups as $group => $permissions) {
            foreach ($permissions as $name => $label) {
                Permission::findOrCreate($name, 'web');
            }
        }

        // Admin gets every permission
        $adminRole = Role::findOrCreate('admin', 'web');
        $adminRole->syncPermissions(Permission::all());

        // Staff starts with no permissions — assign via admin UI
        Role::findOrCreate('staff', 'web');

        $this->command->info('Roles & permissions seeded. Groups: ' . implode(', ', array_keys(self::$groups)));
    }
}
