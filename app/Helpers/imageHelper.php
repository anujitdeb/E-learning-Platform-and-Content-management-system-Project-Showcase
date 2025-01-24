<?php

use Intervention\Image\Laravel\Facades\Image;

function uploadFile($file, $prefix = '', $dir = 'documents')
{
    $fileName = $prefix . time() . '.' . $file->getClientOriginalExtension();
    $path = public_path($dir . '/');

    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
    $file->move($path, $fileName);

    return $dir . '/' . $fileName;
}

function uploadBase64Image($base64, $dir = 'images', $prefix = '', $width = null, $height = null,)
{
    $imageName = $prefix . time() . '.' . 'png';
    $path = public_path($dir . '/');
    // Create directory if it doesn't exist
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
    $image = Image::read($base64);
    // Check if width and height are valid integers
    if (is_numeric($width) && is_numeric($height)) {
        $image->resize((int)$width, (int)$height);
    }

    // Save the image
    $image->save($path . $imageName);

    return $dir . '/' . $imageName;
}


function uploadImage($file, $width, $height, $prefix = '', $dir = 'images')
{
    $imageName = $prefix . time() . '.' . $file->getClientOriginalExtension();
    $path = public_path($dir . '/');

    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
    Image::read($file)->resize($width, $height)->save($path . $imageName);
    return $dir . '/' . $imageName;
}

function uploadSameImage($file, $prefix = '', $dir = 'images')
{
    $imageName = $prefix . time() . '.' . $file->getClientOriginalExtension();
    $path = public_path($dir . '/');

    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
    Image::read($file)->save($path . $imageName);
    return $dir . '/' . $imageName;
}

function deleteImage($path): bool
{
    if ($path && file_exists(public_path($path))) {
        unlink(public_path($path));
        return true;
    }
    return false;
}
