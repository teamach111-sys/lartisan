<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class StorageProxyController extends Controller
{
    /**
     * Proxy files from R2/S3 cloud storage through the app's own domain.
     * This avoids CORS issues when Filament's FileUpload (FilePond) tries to
     * fetch() existing images for preview from a different origin.
     */
    public function __invoke(string $path)
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk(config('filesystems.default', 'public'));

        if (!$disk->exists($path)) {
            abort(404);
        }

        return response()->stream(function () use ($disk, $path) {
            echo $disk->get($path);
        }, 200, [
            'Content-Type' => $disk->mimeType($path) ?: 'application/octet-stream',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
