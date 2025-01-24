<?php

namespace App\Jobs\Promotion;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;


class OfferFirebasePushNotificationJob implements ShouldQueue
{
    use Queueable;

    public $offerNotice;

    /**
     * Create a new job instance.
     */
    public function __construct($offerNotice)
    {
        $this->offerNotice = $offerNotice;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $firebase = (new Factory)
            ->withServiceAccount(storage_path('app/firebase/firebase-auth.json'))
            ->createMessaging();

        $offerNotice = $this->offerNotice;

        $userTokens = User::where(function ($q) use ($offerNotice) {
            if ($offerNotice['status'] == 2) {
                $q->whereNull('student_id');
            }

            if ($offerNotice['status'] == 3) {
                $q->whereNotNull('student_id');
            }
        })->whereNotNull('device_token')->pluck('device_token')->toArray();

        $message = [
            'notification' => [
                'title' => $offerNotice['title'],
                'body' => $offerNotice['description'],
                'image' => $offerNotice['image'] ? asset($offerNotice['image']) : null,
            ],
        ];

        try {
            if (!empty($userTokens)) {
                $response = $firebase->sendMulticast($message, $userTokens); // Batch send

                // $successCount = $response->successes()->count();
                // $failureCount = $response->failures()->count();

                // Remove invalid tokens
                // foreach ($response->failures() as $failedToken => $error) {
                //     User::where('device_token', $failedToken)->update(['device_token' => null]);
                // }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
