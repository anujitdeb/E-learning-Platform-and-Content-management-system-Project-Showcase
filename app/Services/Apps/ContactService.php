<?php

namespace App\Services\Apps;

use App\Models\Contact\Contact;
use App\Models\Contact\ContactContent;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ContactService
{
    const SWW = 'Something went wrong';

    public function getContact()
    {

        try {
            return Contact::with(['contents:id,contact_id,language_id,title,description,btn_name'])
                ->select('id', 'icon', 'whats_app_link', 'messenger_link', 'hotline_number')
                ->where('is_active', true)
                ->firstOrFail();
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function updateContact(array $data): string
    {
        DB::beginTransaction();
        try {
            $contact = $this->getContact();
            if (isset($data['icon'])) {
                $this->deleteIcon($contact->icon);
                $icon = $this->uploadIcon($data['icon']);
            } else {
                $icon = $contact->icon;
            }
            $data['created_by'] = auth('admin-api')->id();
            $data['icon'] = $icon;
            $contact->update($data);
            $contactContents = $this->contactContents($data['contents'], $contact->id);
            ContactContent::where('contact_id', $contact->id)->delete();
            ContactContent::insert($contactContents);

            DB::commit();
            return "Successfully updated.";
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(self::SWW, $e->getCode());
        }
    }

    public function getActiveContact(int $language): Contact
    {
        try {
            return Contact::with([
                'content' => function ($q) use ($language) {
                    $q->where('language_id', $language);
                    $q->select('contact_id', 'title', 'description', 'btn_name');
                }
            ])
                ->where('is_active', true)
                ->select('id','icon', 'whats_app_link', 'messenger_link', 'hotline_number')
                ->first();
        } catch (Exception $e) {
            throw new Exception(self::SWW, $e->getCode());
        }
    }
    private function contactContents(array $contents, int $contactId)
    {
        $contactContents = [];
        foreach ($contents as $content) {
            array_push($contactContents, [
                'contact_id' => $contactId,
                'language_id' => $content['language_id'],
                'title' => $content['title'],
                'description' => $content['description'],
                'btn_name' => $content['btn_name'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        return $contactContents;
    }

    private function uploadIcon($icon)
    {
        $slug = rand(000000, 999999);
        return uploadBase64Image(
            $icon,
            'images/contact',
            'icon_' . $slug,
            600,
            600,
        );
    }
    private function deleteIcon($icon)
    {
        if ($icon && file_exists(public_path($icon))) {
            unlink(public_path($icon));
            return true;
        }
        return false;
    }
}
