<?php

namespace Database\Seeders;

use App\Models\Authorization\permission;
use App\Models\Authorization\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::updateOrCreate([
            'created_by' => 1,
            'name' => "System Admin",
            'is_active' => true,
            'deletable' => false,
        ]);
        $permissions = Permission::pluck('id');
        $role->permissions()->sync($permissions);
    }
}
