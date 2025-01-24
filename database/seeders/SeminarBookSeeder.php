<?php

namespace Database\Seeders;

use App\Models\Seminar\BookSeminar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeminarBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BookSeminar::insert([
            [
                'seminar_id' => 1,
                'user_id' => 1,
                'datetime' => now(),
            ],
            [
                'seminar_id' => 1,
                'user_id' => 2,
                'datetime' => now(),
            ],
            [
                'seminar_id' => 2,
                'user_id' => 1,
                'datetime' => now(),
            ],
            [
                'seminar_id' => 2,
                'user_id' => 2,
                'datetime' => now(),
            ],
        ]);
    }
}
