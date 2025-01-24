<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'student 1',
            'email' => 'student1@gmail.com',
            'password' => bcrypt('student1'),
            'is_active' => 1,
            'number_verified_at' => now(),
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'student 2',
            'email' => 'student2@gmail.com',
            'password' => bcrypt('student2'),
            'is_active' => 1,
            'number_verified_at' => now(),
            'email_verified_at' => now()
        ]);
    }
}
