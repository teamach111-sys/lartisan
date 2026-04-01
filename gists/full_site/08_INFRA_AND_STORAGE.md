---
description: L'Artisan Marketplace - Infrastructure, Storage & Cloud Fixes
---

# Infrastructure & Storage

This module contains the specialized logic for image uploads, cloud storage integration (R2/S3), and production-specific architectural fixes.

## 1. Unified Image Helper
### `app/Helpers/ImageHelper.php`
Centralized service for image compression and environment-aware URL generation (switching between Local Asset links and Cloud Cloudflare R2 links).

```php
public static function getUrl($path): string
{
    if (!$path || $path === 'default.svg') { return asset('imgs/default.svg'); }

    $disk = Storage::disk(config('filesystems.default', 'public'));
    
    try {
        $url = $disk->url($path);
        // Ensure local XAMPP subfolders are supported via url() wrapper
        if (!str_starts_with($url, 'http')) { $url = url($url); }
        return $url;
    } catch (\Exception $e) {
        // Cloud Security Fallback: Temporary Signed URL (24h)
        return $disk->temporaryUrl($path, now()->addHours(24));
    }
}
```

## 2. Storage Configuration
### `config/filesystems.php`
Production-ready configuration for Cloudflare R2 / AWS S3, including a private `storage-proxy` endpoint.

```php
'default' => env('FILESYSTEM_DISK', 'lartisan'),

'disks' => [
    'lartisan' => [
        'driver' => 's3',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => 'auto',
        'bucket' => env('AWS_BUCKET'),
        'url' => env('APP_URL') . '/storage-proxy', // Proxying for CORS bypass
        'endpoint' => env('AWS_ENDPOINT'),
    ],
],
```

## 3. Storage Proxy Controller
### `app/Http/Controllers/StorageProxyController.php`
A critical component that streams cloud files through the app's domain to bypass CORS restrictions for the Filament/FilePond previews.

```php
public function __invoke(string $path)
{
    $disk = Storage::disk(config('filesystems.default', 'public'));
    if (!$disk->exists($path)) { abort(404); }

    return response()->stream(function () use ($disk, $path) {
        echo $disk->get($path);
    }, 200, ['Content-Type' => $disk->mimeType($path)]);
}
```

## 4. Production Hardening
### `app/Providers/AppServiceProvider.php`
Ensures that Filament uploads use the local disk for temporary files, which is significantly faster and more stable than uploading directly to the cloud for intermediate steps.

```php
public function boot(): void
{
    config(['livewire.temporary_file_upload.disk' => 'local']);
}
```

## 5. Environment Readiness Checklist
- **AWS_ACCESS_KEY_ID**: Required for Cloud Storage.
- **AWS_SECRET_ACCESS_KEY**: Required for Cloud Storage.
- **APP_URL**: Must be set correctly for Signed URLs.
- **VITE_PUSHER_APP_KEY**: Required for Real-time chat.
- **MAIL_ENCRYPTION**: Set to `tls` for production delivery.
