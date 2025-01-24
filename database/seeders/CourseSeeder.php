<?php

namespace Database\Seeders;

use App\Models\Course\Course;
use App\Models\Course\CourseContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $course = Course::updateOrCreate([
            'created_by' => 1,
            'course_category_id' => 1,
            'course_id' => 1,
            'slug' => 'mern-stack-development',
            'offline_icon' => 'seeder-images/course/icon.png',
            'online_icon' => 'seeder-images/course/icon.png',
            'seminar_thumbnail' => 'seeder-images/course/thumbnail.jpg',
            'video_thumbnail' => 'seeder-images/course/video_thumbnail.jpg',
            'video_id' => 1,
            'offline_price' => 95000,
            'online_price' => 55000,
            'status' => 1,
            'is_active' => true,
            'bg_color' => '#cf0000',
            'btn_color' => '#ffffff',
        ]);
        CourseContent::updateOrCreate([
            'course_id' => $course->id,
            'created_by' => 1,
            'language_id' => 1,
            'name' => 'MERN Stack Development',
            'course_duration' => '12 Month',
            'lectures_qty' => 96,
            'project_qty' => 20,
            'description' => 'MERN Stack is a combination of four different technologies that is used to develop a website in an efficient manner. In this course, you can gain your expertise in three areas- Web Development, Web Design and App Development. Most of the companies nowadays are using the MERN Stack Programme for its easily customizable, cost-effective features. Enroll in this course to develop your skills in this field.',

        ]);
        CourseContent::updateOrCreate([
            'course_id' => $course->id,
            'created_by' => 1,
            'language_id' => 2,
            'name' => 'মার্ন স্ট্যাক ডেভেলপমেন্ট',
            'course_duration' =>'১২ মাস',
            'lectures_qty' => '৯৬',
            'project_qty' => '২০',
            'description' => 'MERN স্ট্যাক হল চারটি ভিন্ন প্রযুক্তির সংমিশ্রণ যা একটি দক্ষ পদ্ধতিতে ওয়েবসাইট তৈরি করতে ব্যবহৃত হয়। এই কোর্সে, আপনি তিনটি ক্ষেত্রে আপনার দক্ষতা অর্জন করতে পারেন- ওয়েব ডেভেলপমেন্ট, ওয়েব ডিজাইন এবং অ্যাপ ডেভেলপমেন্ট। আজকাল বেশিরভাগ কোম্পানিই MERN স্ট্যাক প্রোগ্রাম ব্যবহার করছে এর সহজে কাস্টমাইজযোগ্য, খরচ-কার্যকর বৈশিষ্ট্যের জন্য। এই ক্ষেত্রে আপনার দক্ষতা বিকাশের জন্য এই কোর্সে নথিভুক্ত করুন।',
        ]);
    }
}
