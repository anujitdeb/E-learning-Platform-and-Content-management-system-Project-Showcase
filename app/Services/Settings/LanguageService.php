<?php

namespace App\Services\Settings;

use App\Models\Settings\Language;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Str;

class LanguageService
{
    const ERROR_SOMETHING_WAS_WRONG = "Something was wrong!";

    public function getLanguages(array $data): LengthAwarePaginator
    {
        try {
            return Language::with('createdBy:id,name,employee_id')
                ->select('id', 'created_by', 'name', 'icon', 'code', 'slug', 'is_active', 'created_at')
                ->orderBy('id', 'DESC')
                ->paginate($data['paginate'] ?? config('app.paginate'));
        } catch (Exception $e) {
            throw new Exception(self::ERROR_SOMETHING_WAS_WRONG, 500);
        }
    }

    function storeLanguage(array $data): Language
    {
        try {
            if ($data['icon']) {
                $icon = uploadBase64Image($data['icon'], 'images/language', 'language-' . Str::slug($data['name']), 160, 70);
            }
            $data['created_by'] = auth('admin-api')->user()->id;
            $data['icon'] = $icon ?? null;
            $data['slug'] = Str::slug($data['name']);
            $language = Language::create($data);
            return $language->load('createdBy:id,name,employee_id');
        } catch (Exception $e) {
            throw new Exception(self::ERROR_SOMETHING_WAS_WRONG, 500);
        }
    }

    public function updateLanguage(array $data, Language $language): Language
    {
        try {
            if ($data['icon']) {
                if ($language->icon && file_exists(public_path($language->icon))) {
                    unlink(public_path($language->icon));
                }
                $icon = uploadBase64Image($data['icon'], 'images/language', 'language-' . Str::slug($data['name']), 160, 70);
            } else {
                $icon = $language->icon;
            }
            $data['created_by'] = auth('admin-api')->user()->id;
            $data['icon'] = $icon ?? null;
            $data['slug'] = Str::slug($data['name']);
            $language->update($data);
            return $language->load('createdBy:id,name,employee_id');
        } catch (Exception $e) {
            throw new Exception(self::ERROR_SOMETHING_WAS_WRONG, 500);
        }
    }

    public function destroyLanguage(Language $language): bool
    {
        try {
            if ($language->icon && file_exists(public_path($language->icon))) {
                unlink(public_path($language->icon));
            }
            return $language->delete();
        } catch (Exception $e) {
            throw new Exception(self::ERROR_SOMETHING_WAS_WRONG, 500);
        }
    }

    public function getActiveLanguages(): Collection
    {
        try {
            return Language::where('is_active', true)
                ->select('id', 'name')->get();
        } catch (Exception $e) {
            throw new Exception(self::ERROR_SOMETHING_WAS_WRONG, 500);
        }
    }
}
