<?php

namespace App\Services\Promotion;

use App\Jobs\Promotion\OfferFirebasePushNotificationJob;
use Carbon\Carbon;
use Exception;
use App\Models\Promotion\OfferNotice;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class OfferNoticeService
{
    const SWW = "Something went wrong!";

    public function getOfferNotice(array $data): LengthAwarePaginator
    {
        try {
            return OfferNotice::with(['createdBy:id,name,employee_id'])
                ->where(function ($q) use ($data) {
                    if (!empty($data['status'])) {
                        $q->where('status', $data['status']);
                    }
                })
                ->select('id', 'created_by', 'title', 'description', 'image', 'thumbnail', 'status', 'end_date', 'created_at')
                ->orderBy('id', 'DESC')
                ->paginate($data['paginate'] ?? config('app.paginate'));
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function storeOfferNotice(array $data): OfferNotice
    {

        try {
            if (isset($data['image'])) {
                $data['image'] = $this->uploadImage($data['image'], 1280, 720);
            }
            if (isset($data['thumbnail'])) {
                $data['thumbnail'] = $this->uploadImage($data['thumbnail'], 300, 300);
            }
            $data['created_by'] = auth('admin-api')->id();
            $offerNotice = OfferNotice::create($data);
            OfferFirebasePushNotificationJob::dispatch($offerNotice);
            $offerNotice->load('createdBy:id,name,employee_id');
            return $offerNotice;
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function updateOfferNotice(OfferNotice $notice, array $data): OfferNotice
    {
        try {
            if (isset($data['image'])) {
                deleteImage($notice->image);
                $data['image'] = $this->uploadImage($data['image'], 1280, 720);
            } else {
                $data['image'] = $notice->image;
            }

            if (isset($data['thumbnail'])) {
                deleteImage($notice->thumbnail);
                $data['thumbnail'] = $this->uploadImage($data['thumbnail'], 300, 300);
            } else {
                $data['thumbnail'] = $notice->thumbnail;
            }

            $data['created_by'] = auth('admin-api')->id();
            $notice->update($data);
            $notice->load('createdBy:id,name,employee_id');
            return $notice;
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function destroyOfferNotice(OfferNotice $notice): bool
    {
        try {
            deleteImage($notice->image);
            deleteImage($notice->thumbnail);
            return $notice->delete();
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function storeDeviceToken(array $data): bool
    {
        try {
            $user = auth('api')->user();
            return $user->update($data);
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function uploadImage($image, int $width, int $height): string
    {
        return uploadBase64Image(
            $image,
            'uploads/images/offer',
            'offer_',
            $width,
            $height,
        );
    }

    public function getOfferNoticesForUser(): Collection
    {
        try {
            $user = auth('api')->user();
            return OfferNotice::where(function ($q) use ($user) {
                if ($user->student_id) {
                    $q->whereIn('status', [1, 3]);
                } else {
                    $q->whereIn('status', [1, 2]);
                }
            })
                ->whereDate('end_date', '>=', Carbon::now()->format('Y-m-d'))
                ->select('id', 'title', 'description', 'image', 'thumbnail', 'end_date', 'created_at')
                ->orderBy('id', 'DESC')
                ->get();
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }
}
