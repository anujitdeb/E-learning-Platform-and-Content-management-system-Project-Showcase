<?php

namespace App\Services\Mentor;

use App\Models\Course\Marketplace;
use App\Models\Mentor\Experience;
use App\Models\Mentor\Mentor;
use App\Models\Mentor\MentorProfile;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MentorService
{
    const SWW = 'Something went wrong';

    public function getMentors(array $data): LengthAwarePaginator
    {
        try {
            return Mentor::with(['createdBy:id,name,employee_id', 'courseCategoryContent:id,name,course_category_id', 'profileContent' => function ($q) {
                $q->where('language_id', 1);
                $q->select('id', 'mentor_id', 'name', 'designation');
            }])
                ->select('id', 'created_by', 'course_category_id', 'image', 'is_active', 'is_head', 'created_at')
                ->orderBy('id', 'DESC')
                ->DataFilter($data)
                ->paginate($data['paginate'] ?? config('app.paginate'));
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function storeMentor(array $data): Mentor
    {
        if (empty($data['image'])) {
            throw new Exception('Please select an image', 500);
        }
        DB::beginTransaction();
        try {
            if ($data['image']) {
                $image = $this->uploadImage($data['image']);
            }
            $data['image'] = $image ?? null;
            $data['created_by'] = auth('admin-api')->id();
            $mentor =  Mentor::create($data);

            $contents = $this->mentorProfileContent($data['contents'], $mentor->id);
            MentorProfile::insert($contents);

            DB::commit();
            return $this->loadRelationData($mentor);
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function updateMentor(array $data, Mentor $mentor): Mentor
    {
        DB::beginTransaction();
        try {
            if (isset($data['image'])) {
                deleteImage($mentor->image);
                $image = $this->uploadImage($data['image']);
            } else {
                $image = $mentor->image;
            }
            $data['image'] = $image ?? null;
            $data['created_by'] = auth('admin-api')->id();
            $mentor->update($data);
            $contents = $this->mentorProfileContent($data['contents'], $mentor->id);
            MentorProfile::where('mentor_id', $mentor->id)->delete();
            MentorProfile::insert($contents);

            DB::commit();
            return $this->loadRelationData($mentor);
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    function showMentor(Mentor $mentor): Mentor
    {
        try {
            return $mentor->load(['contents:id,mentor_id,language_id,name,designation,experience,student_qty']);
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function deleteMentor(Mentor $mentor): bool
    {
        try {
            $deleteStatus = deleteImage($mentor->image);

            if ($deleteStatus) {
                return $mentor->delete();
            }

            throw new Exception(self::SWW, 500);
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    private function mentorProfileContent($contents, $mentorId): array
    {
        $mentorContents = [];

        foreach ($contents as $content) {
            array_push($mentorContents, [
                'mentor_id' => $mentorId,
                'language_id' => $content['language_id'],
                'name' => $content['name'],
                'designation' => $content['designation'],
                'experience' => $content['experience'],
                'student_qty' => $content['student_qty'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        return $mentorContents;
    }

    private function uploadImage($image): string
    {
        $slug = rand(000000, 999999);
        return uploadBase64Image(
            $image,
            'images/mentor',
            'image_' . $slug,
            335,
            393,
        );
    }

    private function loadRelationData($mentor): object
    {
        return $mentor->load(['createdBy:id,name,employee_id', 'courseCategoryContent:id,name,course_category_id', 'profileContent:id,mentor_id,language_id,name,designation']);
    }

    public function getActiveMentors(int $department, int $language): Collection
    {
        try {
            return Mentor::with(['profileContent' => function ($q) use ($language) {
                $q->where('language_id', $language);
                $q->select('mentor_id', 'name', 'designation', 'experience', 'student_qty');
            }])
                ->select('id', 'image', 'is_head', 'higher_key')
                ->where(['is_active' => true, 'course_category_id' => $department])
                ->orderBy('higher_key', 'ASC')
                ->get();
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getMentor(Mentor $mentor, int $language)
    {
        try {
            return $mentor->load(['profileContent' => function ($q) use ($language) {
                $q->where('language_id', $language);
                $q->select('mentor_id', 'name', 'designation', 'experience', 'student_qty');
            }, 'courseCategory.content' => function ($q) use ($language) {
                $q->where('language_id', $language);
                $q->select('course_category_id', 'name');
            }]);
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getMentorExperiences(int $mentor): Collection
    {
        try {
            return Experience::select('type', 'name')
                ->where('mentor_id', $mentor)
                ->get();
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getMentorMarketplaces(int $mentor): Collection
    {
        try {
            return Marketplace::whereHas('mentors', function ($q) use ($mentor) {
                $q->where('marketplace_mentor.mentor_id', $mentor);
            })
                ->select('logo')
                ->get();
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }
}
