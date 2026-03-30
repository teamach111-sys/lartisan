<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Compress and store an image.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param int $quality (0-100)
     * @param int|null $maxWidth
     * @return string Path to the stored file
     */
    public static function compressAndStore(UploadedFile $file, string $directory, int $quality = 70, int $maxWidth = 1200): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::random(40) . '.jpg'; // Store everything as JPG for consistency and compression
        $path = $directory . '/' . $filename;

        // Get image info
        list($width, $height, $type) = getimagesize($file->getRealPath());

        // Create image from file
        switch ($type) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($file->getRealPath());
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($file->getRealPath());
                // Handle transparency for PNG to avoid black backgrounds
                $bg = imagecreatetruecolor($width, $height);
                imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
                imagecopy($bg, $image, 0, 0, 0, 0, $width, $height);
                imagedestroy($image);
                $image = $bg;
                break;
            default:
                // Fallback to standard Laravel store if not supported
                return $file->storePublicly($directory);
        }

        // Resize if needed
        if ($maxWidth && $width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) ($height * ($maxWidth / $width));
            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resizedImage;
        }

        // Capture output to a string
        ob_start();
        imagejpeg($image, null, $quality);
        $imageData = ob_get_clean();

        // Save to storage
        Storage::put($path, $imageData, 'public');

        imagedestroy($image);

        return $path;
    }

    /**
     * Get the correct, absolute URL for a stored file, perfectly supporting both XAMPP subfolders (via asset) and S3 Cloud storage.
     */
    public static function getUrl($path): string
    {
        if (!$path || $path === 'default.svg') {
            return asset('imgs/default.svg');
        }

        // If storing on S3 or another cloud driver, Storage::url is absolute and safe.
        if (config('filesystems.default') !== 'local' && config('filesystems.default') !== 'public') {
            return Storage::url($path);
        }

        // Locally, we use asset() to guarantee perfect subfolder resolution (e.g. for XAMPP users)
        return asset('storage/' . $path);
    }
}
