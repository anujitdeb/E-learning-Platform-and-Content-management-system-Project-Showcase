<?php

namespace App\Services\ContentManagement;

use App\Models\ContentManagement\About;
use App\Models\ContentManagement\AboutContent;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AboutService
{
    const ERROR_SOMETHING_WAS_WRONG = "Something went wrong!";

    public function getAbouts(array $data): LengthAwarePaginator
    {
        try {
            return About::with(['createdBy:id,name,employee_id', 'content' => function ($q) {
                $q->where('language_id', 1);
            }])->select('id', 'created_by', 'image', 'is_active', 'created_at')
                ->orderBy('id', 'DESC')->paginate($data['paginate'] ?? config('app.paginate'));
        } catch (Exception $e) {
            throw new Exception(self::ERROR_SOMETHING_WAS_WRONG, 500);
        }
    }

    public function storeAbout(array $data): About
    {
        DB::beginTransaction();
        try {
            $userID = auth('admin-api')->user()->id;
            $aboutData = array_diff_key($data, array_flip(['contents']));
            $aboutData['created_by'] = $userID;

            if (isset($aboutData['image'])) {
                $aboutData['image'] = $this->uploadImage($aboutData['image']);
            }

            $about = About::create($aboutData);

            $aboutContentData = $this->prepareAboutContentData($data, $about, $userID);

            if (!empty($aboutContentData)) {
                AboutContent::insert($aboutContentData);
            }

            DB::commit();

            $about->load('content:id,title,about_id');
            return $about;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(self::ERROR_SOMETHING_WAS_WRONG, 500);
        }
    }

    public function showAbout(About $about): About
    {
        return $about->load([
            'createdBy:id,name,employee_id',
            'contents'
        ]);
    }

    public function updateAbout(array $data, About $about): About
    {
        DB::beginTransaction();
        try {
            $aboutData = array_diff_key($data, array_flip(['contents']));

            if (isset($aboutData['image'])) {
                deleteImage($about->image);

                $aboutData['image'] = $this->uploadImage($aboutData['image']);
            } else {
                $aboutData['image'] = $about->image;
            }

            $about->update($aboutData);

            if (!empty($data['contents'])) {
                $about->contents()->delete();

                $contents = $this->prepareAboutContentData($data, $about);

                AboutContent::insert($contents);
            }

            DB::commit();

            $about->load('content:id,title,about_id');
            return $about;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(self::ERROR_SOMETHING_WAS_WRONG, 500);
        }
    }

    public function destroyAbout(About $about): bool
    {
        try {
            deleteImage($about->image);

            return $about->delete();
        } catch (Exception $e) {
            throw new Exception(self::ERROR_SOMETHING_WAS_WRONG, 500);
        }
    }

    public function uploadImage($image): string
    {
        return uploadBase64Image(
            $image,
            'uploads/images/about',
            'about_',
            1280,
            720,
        );
    }

    private function prepareAboutContentData(array $data, object $about, int $userID = null): array
    {
        if (!$userID) {
            $userID = auth('admin-api')->user()->id;
        }

        return array_map(function ($content) use ($about, $userID) {
            $content['created_by'] = $userID;
            $content['about_id'] = $about->id;
            $content['created_at'] = Carbon::now();
            $content['updated_at'] = Carbon::now();
            return $content;
        }, $data['contents']);
    }

    public function getActiveAbouts(int $language): Collection
    {
        try {
            return About::with([
                'content' => function ($q) use ($language) {
                    $q->where('language_id', $language);
                    $q->select('title', 'description', 'about_id');
                }
            ])
                ->where('is_active', true)
                ->select('id', 'image')
                ->orderBy('id', 'DESC')
                ->get();
        } catch (Exception $e) {
            throw new Exception(self::ERROR_SOMETHING_WAS_WRONG, 500);
        }
    }
}
