<?php

namespace Database\Seeders;

use App\Models\Course\CourseCategory;
use App\Models\Course\CourseCategoryContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = CourseCategory::updateOrCreate([
            'created_by' => 1,
            'icon' => 'seeder-images/course/wd.png',
            'slug' => 'web-software'
        ]);

        CourseCategoryContent::updateOrCreate([
            'course_category_id' => $category->id,
            'language_id' => 1,
            'name' => 'Web & Software'
        ]);
        CourseCategoryContent::updateOrCreate([
            'course_category_id' => $category->id,
            'language_id' => 2,
            'name' => 'ওয়েব এবং সফটওয়্যার'
        ]);
    }
}
