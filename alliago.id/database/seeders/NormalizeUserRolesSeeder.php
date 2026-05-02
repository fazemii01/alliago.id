<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class NormalizeUserRolesSeeder extends Seeder
{
    /**
     * Assign the "user" role to any accounts that have no role assigned.
     * Safe to run multiple times — idempotent.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'web',
        ]);

        $usersWithoutRoles = User::query()
            ->whereDoesntHave('roles')
            ->get();

        $count = 0;
        foreach ($usersWithoutRoles as $user) {
            $user->assignRole($role);
            $count++;
        }

        $this->command?->info("Assigned 'user' role to {$count} account(s) that had no role.");
    }
}
