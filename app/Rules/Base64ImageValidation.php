<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Base64ImageValidation implements ValidationRule
{
    protected $maxSize; // Maximum allowed file size in kilobytes

    /**
     * Create a new rule instance.
     *
     * @param int $maxSize Maximum size in kilobytes (default: 512 KB)
     */
    public function __construct($maxSize = 10240)
    {
        $this->maxSize = $maxSize;
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the value matches the expected base64 image pattern
        if (!preg_match('/^data:image\/(jpeg|png|jpg|gif);base64,/', $value)) {
            $fail('The :attribute must be a valid base64-encoded image (JPEG, PNG, JPG, or GIF).');
            return;
        }

        // Decode the base64 image data
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $value), true);

        // Check if decoding was successful and if the size is within the allowed limit
        if ($imageData === false || strlen($imageData) > $this->maxSize * 1024) {
            $fail('The :attribute must not exceed ' . $this->maxSize . ' KB.');
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid base64-encoded image (JPEG, PNG, JPG, or GIF) with a maximum size of ' . $this->maxSize . ' KB.';
    }
}
