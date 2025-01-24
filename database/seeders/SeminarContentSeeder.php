<?php

namespace Database\Seeders;

use App\Models\Seminar\SeminarContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeminarContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SeminarContent::create([
            'seminar_id' => 1,
            'language_id' => 1,
            'name' => 'Seminar 1 content 1',
        ]);
    }
}
