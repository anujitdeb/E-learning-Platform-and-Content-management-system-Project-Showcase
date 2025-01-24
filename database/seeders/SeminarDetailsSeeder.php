<?php

namespace Database\Seeders;

use App\Models\Seminar\SeminarDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeminarDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SeminarDetail::create([
            'title' => 'Seminar 1',
            'course_category_id' => 1,
            'course_id' => 1,
            'thumbnail' => 'seeder-images/seminar-1.jpg',
            'created_by' => 1,
            'status' => 1,
        ]);
    }
}
