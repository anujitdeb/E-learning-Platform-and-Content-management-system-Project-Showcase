<?php

namespace App\Services\Seminar;

use App\Models\Seminar\Seminar;
use App\Models\Seminar\SeminarContent;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Seminar\BookSeminar;

class SeminarService
{
    const SOMETHING_WENT_WRONG = 'Something went wrong!';

    public function getAllSeminars(array $data): LengthAwarePaginator
    {
        try {
            return Seminar::with(['content' => function ($query) {
                $query->where('language_id', 1);
                $query->select('id', 'seminar_id', 'language_id', 'name');
            }, 'course.content' => function ($q) {
                $q->where('language_id', 1);
                $q->select('id', 'course_id', 'language_id', 'name');
            }, 'location.content' => function ($q) {
                $q->where('language_id', 1);
                $q->select('id', 'location_id', 'language_id', 'name');
            }, 'createdBy:id,name,employee_id'])
                ->DataFilter($data)
                ->orderBy('id', 'DESC')
                ->select('id', 'seminar_detail_id', 'course_id', 'location_id', 'type', 'datetime', 'link', 'platform', 'created_at', 'created_by', 'is_active', 'seminar_type')
                ->paginate($data['paginate'] ?? config('app.paginate'));
        } catch (Exception $e) {
            throw new Exception(self::SOMETHING_WENT_WRONG, $e->getCode());
        }
    }

    public function storeSeminar(array $data): Seminar
    {
        DB::beginTransaction();
        try {
            if ($data['type'] == 1) {
                $data['link'] = null;
                $data['platform'] = null;
            } else {
                $data['location_id'] = null;
            }
            $data['created_by'] = auth('admin-api')->id();
            $seminar = Seminar::create($data);

            $contents = $this->seminarContent($data['contents'], $seminar->id);
            SeminarContent::insert($contents);
            DB::commit();
            return $seminar->load('content', 'course.content:id,name,course_id', 'location.content:id,name,location_id', 'createdBy:id,name,employee_id');
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(self::SOMETHING_WENT_WRONG, $e->getCode());
        }
    }

    public function show(Seminar $seminar): Seminar
    {
        try {
            return $seminar->load('contents');
        } catch (Exception $e) {
            throw new Exception(self::SOMETHING_WENT_WRONG, $e->getCode());
        }
    }

    public function updateSeminar(array $data, Seminar $seminar): Seminar
    {
        DB::beginTransaction();
        try {
            if ($data['type'] == 1) {
                $data['link'] = null;
                $data['platform'] = null;
            } else {
                $data['location_id'] = null;
            }

            $data['updated_by'] = auth('admin-api')->id();
            $seminar->update($data);

            $contents = $this->seminarContent($data['contents'], $seminar->id);
            SeminarContent::where('seminar_id', $seminar->id)->delete();
            SeminarContent::insert($contents);
            DB::commit();
            return $seminar->load('content', 'course.content:id,name,course_id', 'location.content:id,name,location_id', 'createdBy:id,name,employee_id');
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(self::SOMETHING_WENT_WRONG, $e->getCode());
        }
    }

    public function deleteSeminar(Seminar $seminar): void
    {
        try {
            $seminar->delete();
        } catch (Exception $e) {
            throw new Exception(self::SOMETHING_WENT_WRONG, $e->getCode());
        }
    }

    private function seminarContent(array $contents, int $seminarId): array
    {
        $seminarContents = [];
        foreach ($contents as $content) {
            array_push($seminarContents, [
                'seminar_id' => $seminarId,
                'language_id' => $content['language_id'],
                'name' => $content['name'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        return $seminarContents;
    }

    public function getSeminars(int $language, array $data): Collection
    {
        try {
            $seminar = Seminar::with([
                'course:id,seminar_thumbnail',
                'content' => function ($q) use ($language) {
                    $q->where('language_id', $language);
                    $q->select('name', 'seminar_id');
                },
                'location:id,latitude,longitude,marker_title,marker_description',
                'location.content' => function ($q) use ($language) {
                    $q->where('language_id', $language);
                    $q->select('location_id', 'name', 'address');
                }
            ])
                ->DataFilter($data)
                ->whereDate('datetime', '>=', Carbon::now()->format('Y-m-d'))
                ->select('id', 'datetime', 'type', 'location_id', 'platform', 'course_id', 'seminar_type', 'is_active')
                ->where('is_active', true)
                ->orderBy('datetime', 'DESC');

            if (isset($data['limit'])) {
                $seminar->limit($data['limit']);
            }
            return $seminar->get();
        } catch (Exception $e) {
            throw new Exception(self::SOMETHING_WENT_WRONG, $e->getCode());
        }
    }

    public function showSeminar(int $language, int $seminar): Seminar
    {
        try {
            return Seminar::with([
                'content' => function ($q) use ($language) {
                    $q->where('language_id', $language);
                    $q->select('name', 'seminar_id');
                },
                'location:id,latitude,longitude,marker_title,marker_description',
                'location.content' => function ($q) use ($language) {
                    $q->where('language_id', $language);
                    $q->select('location_id', 'name', 'address');
                },
                'seminarDetail:id,course_category_id,video_id,thumbnail',
                'seminarDetail.detailContent' => function ($q) use ($language) {
                    $q->where('language_id', $language);
                    $q->select('seminar_detail_id', 'contents');
                }
            ])
                ->where('is_active', true)
                ->select('id', 'seminar_detail_id', 'location_id', 'type', 'datetime', 'link', 'platform', 'seminar_type', 'is_active')
                ->findOrFail($seminar);
        } catch (Exception $e) {
            throw new Exception(self::SOMETHING_WENT_WRONG, $e->getCode());
        }
    }

    public function getBookedSeminars(int $userID, int $languageID = 1): Collection
    {
        try {
            $seminarId = DB::table('book_seminars')->where('user_id', $userID)
                ->whereDate('datetime', '>=', Carbon::now()->format('Y-m-d'))
                ->pluck('seminar_id');
            $seminar = Seminar::with([
                'content' => function ($q) use ($languageID) {
                    $q->where('language_id', $languageID);
                    $q->select('name', 'seminar_id');
                },
                'course:id,seminar_thumbnail',
                'location:id,latitude,longitude,marker_title,marker_description',
                'location.content' => function ($q) use ($languageID) {
                    $q->where('language_id', $languageID);
                    $q->select('location_id', 'name', 'address');
                }
            ])
                ->whereIn('id', $seminarId)
                ->orderBy('datetime', 'asc')
                ->select('id', 'datetime', 'type', 'link', 'location_id', 'platform', 'course_id', 'seminar_type', 'is_active');
            if (!empty(request()->limit)) {
                $seminar->limit(request()->limit);
            }
            return $seminar->get();
        } catch (Exception $e) {
            throw new Exception(self::SOMETHING_WENT_WRONG, $e->getCode());
        }
    }

    public function bookSeminar(Seminar $seminar, int $userId): string
    {
        try {
            $booked = BookSeminar::firstOrCreate([
                'seminar_id' => $seminar->id,
                'user_id' => $userId,
            ], [
                'datetime' => $seminar->datetime,
            ]);

            return $booked->wasRecentlyCreated
                ? 'Seminar booked successfully'
                : 'You have already booked this seminar';
        } catch (Exception $e) {
            throw new Exception(self::SOMETHING_WENT_WRONG, $e->getCode());
        }
    }
}
