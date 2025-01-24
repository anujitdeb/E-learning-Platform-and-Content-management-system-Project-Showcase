<?php

namespace App\Services\Course;

use App\Models\Course\Course;
use App\Models\Course\CourseContent;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseContentService
{
    public function getCourseWiseCourseContent(Course $course): object
    {
        try {
            return $course->load('contents:id,course_id,language_id,name,course_duration,lectures_qty,project_qty,description');
        } catch (Exception $e) {
            throw new Exception("Something went wrong!", 500);
        }
    }

    public function updateCourseContent(array $data, Course $course): Course
    {
        DB::beginTransaction();

        try {
            $userID = auth('admin-api')->user()->id;
            $courseData = array_diff_key($data, array_flip(['contents']));
            $courseData['slug'] = Str::slug($data['slug'] ?? $data['contents'][0]['name']);
            $courseData['created_by'] = $userID;

            $course->update($courseData);

            if (!empty($data['contents'])) {
                $course->contents()->delete();

                $contents = $this->prepareCourseContentData($data, $course, $userID);

                CourseContent::insert($contents);
            }

            DB::commit();
            return $course;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Something went wrong!", 500);
        }
    }

    private function prepareCourseContentData(array $data, object $course, int $userID): array
    {
        return array_map(function ($content) use ($course, $userID) {
            $content['course_id'] = $course->id;
            $content['created_at'] = Carbon::now();
            $content['updated_at'] = Carbon::now();

            return $content;
        }, $data['contents']);
    }
}
