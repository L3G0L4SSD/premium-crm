<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Full system access', 'is_active' => true],
            ['name' => 'Manager', 'slug' => 'manager', 'description' => 'Department manager access', 'is_active' => true],
            ['name' => 'Staff', 'slug' => 'staff', 'description' => 'Basic staff access', 'is_active' => true],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug'=> $role['slug']], $role);
        }

    }
}
