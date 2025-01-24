<?php

namespace App\Services\Course;

use App\Models\Course\CourseCategory;
use App\Models\Course\CourseCategoryContent;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Str;

class CourseCategoryService
{
    const SWW = "Something went wrong!";

    public function getCourseCategories(array $data): LengthAwarePaginator | Collection
    {
        try {
            $categories = CourseCategory::with(['createdBy:id,name,employee_id', 'content' => function ($q) {
                $q->where('language_id', 1);
            }])->select('id', 'created_by', 'icon', 'slug', 'higher_key', 'is_active', 'created_at')
                ->orderBy('higher_key', 'ASC')->orderBy('id', 'ASC');
            if (!empty($data['paginate']) || !empty($data['page'])) {
                return $categories->paginate($data['paginate'] ?? config('app.paginate'));
            }
            return $categories->where('is_active', true)->get();
        } catch (Exception $e) {
            throw new Exception(self::SWW, 500);
        }
    }

    function storeCourseCategory(array $data): CourseCategory
    {
        if (empty($data['icon'])) {
            throw new Exception('Please select an icon', 500);
        }

        DB::beginTransaction();
        try {
            $slug = Str::slug($data['contents'][0]['name']);
            $higherKey = (CourseCategory::max('higher_key') ?? 0) + 1;
            $icon = uploadBase64Image($data['icon'], 'images/course_category', 'icon-' . $slug, 300, 300);

            $courseCategory = CourseCategory::create([
                'created_by' => auth('admin-api')->user()->id,
                'icon'     => $icon,
                'is_active'     => $data['is_active'],
                'slug'     => $slug,
                'higher_key'     => $higherKey,
            ]);

            $categoryContents = $this->categoryContents($data['contents'], $courseCategory->id);
            CourseCategoryContent::insert($categoryContents);
            DB::commit();

            $courseCategory->load(['createdBy:id,name,employee_id', 'content']);
            return $courseCategory;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(self::SWW, 500);
        }
    }

    function showCourseCategory(CourseCategory $category): CourseCategory
    {
        return $category->load([
            'contents',
        ]);
    }

    function updateCourseCategory(array $data, CourseCategory $category): CourseCategory
    {
        DB::beginTransaction();
        try {
            if (!empty($data['icon'])) {
                if ($category->icon && file_exists(public_path($category->icon))) {
                    unlink(public_path($category->icon));
                }
                $icon = uploadBase64Image($data['icon'], 'images/course_category', 'icon-' .  $category->slug, 300, 300);
            } else {
                $icon = $category->icon;
            }

            $category->update([
                'created_by' => auth('admin-api')->user()->id,
                'icon' => $icon,
                'is_active'     => $data['is_active'],
            ]);

            $categoryContents = $this->categoryContents($data['contents'], $category->id);
            CourseCategoryContent::where('course_category_id', $category->id)->delete();
            CourseCategoryContent::insert($categoryContents);

            DB::commit();

            $category->load(['createdBy:id,name,employee_id', 'content']);
            return $category;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(self::SWW, 500);
        }
    }

    function categoryContents(array $contents, int $courseCategoryId): array
    {
        $categoryContents = [];

        foreach ($contents as $content) {
            array_push($categoryContents, [
                'course_category_id' => $courseCategoryId,
                'language_id' => $content['language_id'],
                'name' => $content['name'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return $categoryContents;
    }

    public function destroyCourseCategory(CourseCategory $category): bool
    {
        try {
            if ($category->icon && file_exists(public_path($category->icon))) {
                unlink(public_path($category->icon));
            }
            return $category->delete();
        } catch (Exception $e) {
            throw new Exception(self::SWW, 500);
        }
    }

    public function updateHigherKey(array $data): void
    {
        DB::beginTransaction();
        try {
            $i = 1;
            foreach ($data['contents'] as $category) {
                CourseCategory::find($category['id'])->update([
                    'higher_key' => $i
                ]);
                $i++;
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(self::SWW, 500);
        }
    }

    public function appCourseCategories(int $language): Collection
    {
        try {
            return CourseCategory::with([
                'content' => function ($q) use ($language) {
                    $q->where('language_id', $language);
                    $q->select('name', 'course_category_id');
                }
            ])
                ->select('id', 'icon', 'slug')
                ->orderBy('higher_key', 'ASC')
                ->orderBy('id', 'ASC')
                ->where('is_active', true)->get();
        } catch (Exception $e) {
            throw new Exception(self::SWW, 500);
        }
    }

    public function activeCourseCategories(): Collection
    {
        try {
            return CourseCategory::with(['content' => function ($q) {
                $q->where('language_id', 1)->select('id', 'course_category_id', 'name');
            }])->where('is_active', true)->select('id')->get();
        } catch (Exception $e) {
            throw new Exception(self::SWW, 500);
        }
    }
}
