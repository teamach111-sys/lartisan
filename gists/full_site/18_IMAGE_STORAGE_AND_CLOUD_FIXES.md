---
description: L'Artisan Marketplace - Image Storage, Cloud Hardening & Signed URLs
---

# Image Storage: Cloud Hardening & Signed URLs

This document covers the implementation of a robust, environment-aware image storage system that supports both local development (XAMPP) and secure cloud production (Laravel Cloud/S3/R2).

## 1. Environment-Aware Image Utility

A centralized `ImageHelper` was created to handle image compression and URL resolution. It automatically detects the active storage driver to decide whether to serve local asset links or secure signed cloud links.

### `app/Helpers/ImageHelper.php`
```php
<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Compress and store an image.
     */
    public static function compressAndStore(UploadedFile $file, string $directory, int $quality = 70, int $maxWidth = 1200): string
    {
        $filename = Str::random(40) . '.jpg';
        $path = $directory . '/' . $filename;

        list($width, $height, $type) = getimagesize($file->getRealPath());

        // Create image resource (handles JPEG/PNG)
        // ... (Compression logic)

        // Save to storage using the default configured disk
        Storage::disk(config('filesystems.default', 'public'))->put($path, $imageData, 'public');

        return $path;
    }

    /**
     * Get the correct, absolute URL (Supports Signed Cloud URLs).
     */
    public static function getUrl($path): string
    {
        if (is_array($path)) { $path = $path[0] ?? null; }
        if (!$path || $path === 'default.svg') { return asset('imgs/default.svg'); }

        $disk = Storage::disk(config('filesystems.default', 'public'));
        
        try {
            // Priority: Generate signed temporary URL for cloud security (S3/R2)
            return $disk->temporaryUrl($path, now()->addHours(24));
        } catch (\RuntimeException $e) {
            // Fallback: Use standard URL for local disks
            return url($disk->url($path));
        }
    }
}
```

## 2. Standardizing Storage Disks

To ensure 100% compatibility across environments, all file upload components (Filament and Public Controllers) were updated to use the application's **default configured disk** rather than hardcoding a local-only "public" disk.

### Filament Upload Fix (Example):
```php
FileUpload::make('images')
    ->disk(config('filesystems.default', 'public')) // Dynamically switches between Local and S3
    ->directory('produits')
    ->image();
```

## 3. Production Deployment Security

Laravel Cloud (using Cloudflare R2) protects files by default. Simple links are blocked, requiring signatures.

- **Signed URLs**: The platform now generates `?expires=...&signature=...` links automatically for all images on the site.
- **Bypassing `storage:link`**: Since images now live in the cloud, the `php artisan storage:link` command is no longer required for production deployments.

## 4. Key Benefits
*   **Zero-Config Deployment**: Works instantly on fresh cloud installs.
*   **Secure Storage**: Files are private by default but viewable via 24-hour signed links.
*   **Developer Experience**: Continues to work perfectly on local XAMPP/localhost with standard symlinks.
