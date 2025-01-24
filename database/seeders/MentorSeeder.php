<?php

namespace Database\Seeders;

use App\Models\Mentor\Mentor;
use App\Models\Mentor\MentorProfile;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MentorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mentor = Mentor::updateOrCreate([
            'course_category_id' => 1,
            'image' => 'seeder-images/user.png',
            'is_active' => true,
            'is_head' => false,
        ]);
        MentorProfile::updateOrCreate([
            'mentor_id' => $mentor->id,
            'language_id' => 1,
            'name' => "Eftekhar Alam",
            'designation' => "Jr. Software Engineer",
            'experience' => "4 Years",
            'student_qty' => "200",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        MentorProfile::updateOrCreate([
            'mentor_id' => $mentor->id,
            'language_id' => 2,
            'name' => "ইফতেখার আলম",
            'designation' => "জুনিয়র সফটওয়্যার ইঞ্জিনিয়ার",
            'experience' => "৪ বছর",
            'student_qty' => "২০০",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
