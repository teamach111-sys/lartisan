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

        // Save to storage using the default configured disk (e.g. S3 in cloud, Public locally)
        // Explicitly set public visibility to ensure standard URL accessibility where permitted.
        Storage::disk(config('filesystems.default', 'public'))->put($path, $imageData, 'public');

        imagedestroy($image);

        return $path;
    }

    /**
     * Get the correct, absolute URL for a stored file, perfectly supporting both XAMPP subfolders (via asset) and S3 Cloud storage.
     */
    public static function getUrl($path): string
    {
        // Handle array (take first item) or null/empty
        if (is_array($path)) {
            $path = $path[0] ?? null;
        }

        if (!$path || $path === 'default.svg') {
            return asset('imgs/default.svg');
        }

        // Simply use the default disk's URL method (just like Filament does).
        // On Cloud (S3/R2), we often need a signed, temporary URL to view private files.
        // We'll try to generate a temporary URL that's valid for 24 hours.
        $disk = Storage::disk(config('filesystems.default', 'public'));
        
        try {
            // Check if the disk supports temporary URLs (S3/R2/Cloud disks do)
            return $disk->temporaryUrl($path, now()->addHours(24));
        } catch (\RuntimeException $e) {
            // Fallback for disks that don't support temporary URLs (like the Local disk)
            // Absolute URL wrapping to ensure its valid on any domain.
            return url($disk->url($path));
        }
    }
}
