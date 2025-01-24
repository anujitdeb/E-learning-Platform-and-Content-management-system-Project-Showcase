<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'role_id' => 1, // Assuming a role with ID 1 exists
            'employee_id' => 10860,
            'name' => 'Rakibul Islam',
            'email' => 'admin@admin.com',
            'number' => 1234567890,
            'password' => Hash::make('12345678'), // Use a secure password
            'email_verified_at' => now(),
            'image' => null,
            'is_active' => true,
            'deleteable' => true,
        ]);
    }
}
