<?php

namespace Database\Seeders;

use App\Models\ContentManagement\About;
use App\Models\ContentManagement\AboutContent;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $about = About::updateOrCreate([
            'created_by' => 1,
            'image' => 'seeder-images/1.png',
            'is_active' => true,
        ]);
        AboutContent::updateOrCreate([
            'about_id' => $about->id,
            'language_id' => 1,
            'title' => 'The Ten Outstanding Young Persons of Bangladesh [TOYP] 2021',
            'description' => 'Our honorable CEO Mr. Monir Hosen received the TOYP 2021 award for his incredible contribution to the development of the IT sector. This award is given to the top 10 young entrepreneurs for outstanding performance.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        AboutContent::updateOrCreate([
            'about_id' => $about->id,
            'language_id' => 2,
            'title' => 'বাংলাদেশের দশটি অসামান্য তরুণ ব্যক্তি [TOYP] ২০২১',
            'description' => 'আমাদের মাননীয় সিইও জনাব মনির হোসেন আইটি সেক্টরের উন্নয়নে অবিশ্বাস্য অবদানের জন্য TOYP ২০২১ পুরস্কার পেয়েছেন। অসামান্য পারফরম্যান্সের জন্য সেরা ১০ তরুণ উদ্যোক্তাকে এই পুরস্কার দেওয়া হয়।',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
