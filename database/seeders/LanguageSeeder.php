<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Str;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('languages')->insert([
            [
                'created_by' => 1,
                'name' => 'English',
                'slug' => Str::slug('English'),
                'icon' => 'seeder-images/language/english.png',
                'code' => 'EN',
                'is_active' => true,
                'created_at'=>Carbon::now()
            ],
            [
                'created_by' => 1,
                'name' => 'বাংলা',
                'slug' => Str::slug('বাংলা'),
                'icon' => 'seeder-images/language/bangla.png',
                'code' => 'বাং',
                'is_active' => true,
                'created_at'=>Carbon::now()
            ]
        ]);
    }
}
