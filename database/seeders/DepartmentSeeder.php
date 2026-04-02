<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Admin','slug' => 'admin', 'description' => 'System administration department','is_active' => true,],
            ['name' => 'Sales','slug' => 'sales','description' => 'Sales department','is_active' => true,],
            ['name' => 'Support','slug' => 'support','description' => 'Customer support department','is_active' => true,],
            ['name' => 'Finance','slug' => 'finance','description' => 'Finance department','is_active' => true,]
        ];
            foreach ($departments as $department) {
                Department::firstOrCreate(['slug' => $department['slug']], $department);
            }
        ;
    }


}
