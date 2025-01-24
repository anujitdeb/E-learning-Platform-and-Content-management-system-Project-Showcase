<?php

namespace Database\Seeders;

use App\Models\Seminar\Seminar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeminarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Seminar::create([
            'seminar_detail_id' => 1,
            'location_id' => 1,
            'course_id' => 1,
            'type' => 1,
            'datetime' => now(),
            'link' => 'https://example.com',
        ]);

        Seminar::create([
            'seminar_detail_id' => 1,
            'location_id' => 1,
            'course_id' => 1,
            'type' => 2,
            'datetime' => now(),
            'link' => 'https://example.com',
        ]);
    }
}
