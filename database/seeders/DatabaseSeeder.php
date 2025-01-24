<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            AdminSeeder::class,
            LanguageSeeder::class,
            AchievementSeeder::class,
            AboutSeeder::class,
            FacilitiesSeeder::class,
            SoftwareSeeder::class,
            CourseCategorySeeder::class,
            CourseSeeder::class,
            SuccessStorySeeder::class,
            SuccessStoryCategory::class,
            HomePageSeeder::class,
            ContactSeeder::class,
            MentorSeeder::class,
            CourseSeeder::class,
            CourseCurriculumSeeder::class,
            HeaderCountdownSeeder::class,
            SeminarDetailsSeeder::class,
            // LocationSeeder::class,
            UserSeeder::class,
            // SeminarSeeder::class,
            // SeminarContentSeeder::class,
            // SeminarBookSeeder::class,
            ConditionPageSeeder::class
        ]);
    }
}
