<?php

namespace App\Services\Course;

use App\Models\Course\Course;
use App\Models\Course\CourseCategory;
use App\Models\Course\CourseContent;
use App\Models\Course\CourseCurriculum;
use App\Models\Course\CourseFacility;
use App\Models\Course\Software;
use App\Models\SuccessStory\SuccessStory;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseService
{
    const SWW = "Something went wrong!";

    public function getCourses(array $data): LengthAwarePaginator
    {
        try {
            return Course::with([
                'createdBy:id,name,employee_id',
                'content' => function ($q) {
                    $q->where('language_id', 1);
                    $q->select('id', 'name', 'course_id');
                },
                'category.content' => function ($q) {
                    $q->where('language_id', 1);
                    $q->select('id', 'name', 'course_category_id');
                },
            ])
                ->select('id', 'created_by', 'slug', 'is_active', 'bg_color', 'btn_color', 'status', 'created_at', 'course_category_id')
                ->orderBy('id', 'DESC')
                ->paginate($data['paginate'] ?? config('app.paginate'));
        } catch (Exception $e) {
            throw new Exception(self::SWW, 500);
        }
    }

    public function getSingleCourse(Course $course, int $language): Course
    {
        try {
            return $course->load([
                'content' => function ($q) use ($language) {
                    $q->where('language_id', $language);
                    $q->select('course_id', 'name', 'course_duration', 'lectures_qty', 'project_qty', 'description');
                },
            ]);
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getCourseCurriculums(int $course_id, int $language): ?CourseCurriculum
    {
        try {
            return CourseCurriculum::where(['course_id' => $course_id, 'language_id' => $language])
                ->select('data')
                ->first();
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getSuccessStories(int $course_id): Collection
    {
        try {
            return SuccessStory::where('course_id', $course_id)->select('thumbnail', 'vedio_id')->get();
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getFacilities(int $course_id, int $language, array $data): Collection
    {
        try {
            return CourseFacility::with(['facility' => function ($q) {
                $q->select('id', 'video_id', 'icon', 'thumbnail');
            }, 'facility.content' => function ($q) use ($language) {
                $q->where('language_id', $language);
                $q->select('facility_id', 'title', 'description');
            }])
                ->where('course_id', $course_id)
                ->DataFilter($data)
                ->select('facility_id')
                ->get();
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getSoftwares(int $course_id, int $language): Collection
    {
        try {
            return Software::with(['content' => function ($q) use ($language) {
                $q->where('language_id', $language);
                $q->select('name', 'software_id');
            }])
                ->whereHas('courses', function ($q) use ($course_id) {
                    $q->where('course_software.course_id', $course_id);
                })
                ->select('id', 'icon')
                ->get();
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function storeCourse(array $data): Course
    {
        DB::beginTransaction();
        try {
            $userID = auth('admin-api')->user()->id;

            $courseData = array_diff_key($data, array_flip(['contents']));
            $courseData['created_by'] = $userID;

            if (!isset($courseData['slug'])) {
                $courseData['slug'] = Str::slug($data['contents'][0]['name']);
            } else {
                $courseData['slug'] = Str::slug($courseData['slug']);
            }

            $course = Course::create($courseData);

            $courseContentData = $this->prepareCourseContentData($data, $course);

            if (count($courseContentData) > 0) {
                CourseContent::insert($courseContentData);
            } else {
                throw new Exception("Data not found", 404);
            }

            DB::commit();

            $course->load('content:id,name,course_id');
            return $course;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(self::SWW, 500);
        }
    }

    public function destroyCourse(Course $course): bool
    {
        try {
            if ($course->offline_icon) {
                deleteImage($course->offline_icon);
            }
            if ($course->online_icon) {
                deleteImage($course->online_icon);
            }
            if ($course->seminar_thumbnail) {
                deleteImage($course->seminar_thumbnail);
            }
            if ($course->video_thumbnail) {
                deleteImage($course->video_thumbnail);
            }

            return $course->delete();
        } catch (Exception $e) {
            throw new Exception(self::SWW, 500);
        }
    }

    public function courseStatusUpdate(Course $course): array
    {
        try {
            if ($course->is_active) {
                $course->update(['is_active' => false]);
                return [
                    'message' => "Course status successfully deactivated.",
                    'data' => false
                ];
            }

            $course->load([
                'content:course_id,course_duration,lectures_qty,project_qty,description',
                'curriculums:course_id',
                'softwares:icon',
                'courseFacilities:course_id'
            ]);

            $hasIncompleteRelations = !$this->courseContentIsEmpty($course->content) &&
                !$course->curriculums->isEmpty() &&
                !$course->softwares->isEmpty() &&
                !$course->courseFacilities->isEmpty();

            if ($hasIncompleteRelations) {
                $course->update(['is_active' => true]);
                return [
                    'message' => "Course status successfully activated.",
                    'data' => true
                ];
            }

            throw new Exception("All course related fields are not filled yet!", 403);
        } catch (Exception $e) {
            throw new Exception($e->getCode() == 403 ? $e->getMessage() : self::SWW, $e->getCode());
        }
    }

    public function getActiveCourses()
    {
        try {
            return Course::with(['content' => function ($q) {
                $q->where('language_id', 1);
                $q->select('id', 'name', 'course_id');
            }])
                ->where('is_active', true)
                ->get();
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getActiveCourseWithFilter(array $data, int $language): Collection
    {
        try {
            return Course::with(['content' => function ($q) use ($language) {
                $q->where('language_id', $language);
                $q->select('course_id', 'name', 'course_duration', 'lectures_qty', 'project_qty');
            }])
                ->where('is_active', true)
                ->select('id', 'slug', 'offline_icon', 'bg_color', 'btn_color', 'online_icon', 'offline_price', 'online_price', 'status', 'video_thumbnail')
                ->DataFilter($data)
                ->get();
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getDepartmentsWithCourses(int $language, array $data): Collection
    {
        try {
            return CourseCategory::with(['content' => function ($q) use ($language) {
                $q->where('language_id', $language);
                $q->select('name', 'course_category_id');
            }, 'courses' => function ($course) use ($data) {
                $course->DataFilter($data)->where('is_active', true);
            }, 'courses.content' => function ($courseContent) use ($language) {
                $courseContent->where(['is_active' => true, 'language_id' => $language]);
            }])
                ->where('is_active', true)
                ->select('id', 'slug')
                ->get();
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    private function prepareCourseContentData(array $data, object $course): array
    {
        return array_map(function ($content) use ($course) {
            $content['course_id'] = $course->id;
            $content['created_at'] = Carbon::now();
            $content['updated_at'] = Carbon::now();
            return $content;
        }, $data['contents']);
    }

    private function courseContentIsEmpty(object $content): bool
    {
        return empty($content->course_duration) ||
            empty($content->lectures_qty) ||
            empty($content->project_qty) ||
            empty($content->description);
    }
}
