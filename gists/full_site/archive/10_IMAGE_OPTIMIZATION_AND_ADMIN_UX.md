---
description: L'Artisan Marketplace - Image Optimization & Admin Panel Details
---

# Image Optimization & Admin Details

This file contains modifications made to permanently fix image upload sizes (preventing PostTooLargeExceptions), heavily compress images both Server-side and Client-side for zero-config deployment to Laravel Cloud, and sort Filament admin tables by newest entries first.

## 1. Server-Side Image Compression Helper

This class resizes uploaded images to a maximum width of 1200px and saves them at 70% quality using native PHP GD, drastically saving storage space.

### `app/Helpers/ImageHelper.php`
```php
<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Compress and store an uploaded image using GD.
     */
    public static function compressAndStore(UploadedFile $file, string $directory, int $maxWidth = 1200, int $quality = 70): string
    {
        $mime = $file->getMimeType();
        $sourcePath = $file->getRealPath();

        // Load image resource based on mime type
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                $sourceImage = imagecreatefromwebp($sourcePath);
                break;
            default:
                // If not a supported compressible type, use standard store
                return $file->store($directory, 'public');
        }

        if (!$sourceImage) {
            return $file->store($directory, 'public');
        }

        // Get dimensions
        $width = imagesx($sourceImage);
        $height = imagesy($sourceImage);

        // Calculate new dimensions keeping aspect ratio
        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) ($height * ($maxWidth / $width));
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        // Create new canvas
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        // Handle transparency for PNG/WebP conversions to JPG
        $whiteBackground = imagecolorallocate($newImage, 255, 255, 255);
        imagefill($newImage, 0, 0, $whiteBackground);

        // Copy and resize
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Generate filename
        $filename = Str::random(40) . '.jpg';
        $fullPathStr = "$directory/$filename";
        $absolutePath = storage_path('app/public/' . $fullPathStr);

        // Ensure directory exists
        if (!file_exists(storage_path("app/public/$directory"))) {
            mkdir(storage_path("app/public/$directory"), 0755, true);
        }

        // Output and compress as JPG
        imagejpeg($newImage, $absolutePath, $quality);

        // Cleanup memory
        imagedestroy($sourceImage);
        imagedestroy($newImage);

        return $fullPathStr;
    }
}
```

## 2. Client-Side (JS) Image Compressor Component

This Blade component uses the browser's Canvas API to intercept file inputs, compress them instantly inside the user's browser, and swap the file before form submission. This avoids server `PostTooLargeException` limits completely.

### `resources/views/components/image-compressor.blade.php`
```html
<script>
    class ImageCompressor {
        static async compress(file, maxWidth = 1200, quality = 0.7) {
            return new Promise((resolve, reject) => {
                if (!file || !file.type.match(/image.*/)) {
                    resolve(file); // Not an image, just return the original file
                    return;
                }

                const img = new Image();
                const reader = new FileReader();

                reader.onload = (e) => {
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        let width = img.width;
                        let height = img.height;

                        if (width > maxWidth) {
                            height = Math.round(height * (maxWidth / width));
                            width = maxWidth;
                        }

                        canvas.width = width;
                        canvas.height = height;

                        const ctx = canvas.getContext('2d');
                        ctx.fillStyle = '#FFFFFF';
                        ctx.fillRect(0, 0, width, height);
                        ctx.drawImage(img, 0, 0, width, height);

                        canvas.toBlob((blob) => {
                            if (blob) {
                                const compressedFile = new File([blob], file.name.replace(/\.[^/.]+$/, "") + ".jpg", {
                                    type: 'image/jpeg',
                                    lastModified: Date.now(),
                                });
                                resolve(compressedFile);
                            } else {
                                reject(new Error('Canvas to Blob failed.'));
                            }
                        }, 'image/jpeg', quality);
                    };
                    img.onerror = () => reject(new Error('Failed to load image.'));
                    img.src = e.target.result;
                };
                reader.onerror = () => reject(new Error('Failed to read file.'));
                reader.readAsDataURL(file);
            });
        }

        static replaceFileInput(inputElement, newFile) {
            try {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(newFile);
                inputElement.files = dataTransfer.files;
                return true;
            } catch (e) {
                console.error("DataTransfer not supported by this browser.", e);
                return false;
            }
        }
    }
    
    // Rendre disponible globalement
    window.ImageCompressor = ImageCompressor;
</script>
```

Add `<x-image-compressor />` to your base layouts (`layoutdash.blade.php` and `auth/register.blade.php`) right before the closing `</body>` tag.

## 3. Form Javascript Implementation Examples

In your blade files containing file inputs (e.g. `produit/create.blade.php`, `produit/edit.blade.php`, `profil.blade.php`, `auth/register.blade.php`), update the `onchange="previewIndividual(this)"` logic:

### Client-Side Validation & Compression Example (`produit/create.blade.php`)
```javascript
// Example usage for an individual input
async function previewIndividual(input, index) {
    let file = input.files[0];
    if (file) {
        // Show a small loading state on the placeholder
        const placeholder = document.getElementById(`placeholder-${index}`);
        if (placeholder) {
            const originalHTML = placeholder.innerHTML;
            placeholder.innerHTML = '<svg class="animate-spin h-8 w-8 text-[#fb663f]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="text-[10px] font-bold mt-1 uppercase text-[#fb663f]">Compression...</span>';
            
            try {
                if (window.ImageCompressor) {
                    file = await window.ImageCompressor.compress(file);
                    window.ImageCompressor.replaceFileInput(input, file);
                }
            } catch (e) {
                console.error("Compression failed", e);
            }
            
            placeholder.innerHTML = originalHTML; // restore if needed
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById(`preview-${index}`);
            // ... logic to reveal image preview
        }
        reader.readAsDataURL(file);
    }
}

// Form validation for TOTAL file size on Submit
document.querySelector('form').addEventListener('submit', function(e) {
    const files = document.querySelectorAll('input[type="file"]');
    let totalSize = 0;
    const maxSizePerFile = 2 * 1024 * 1024; // 2MB
    const maxTotalSize = 10 * 1024 * 1024; // 10MB
    let isValid = true;

    files.forEach((input, index) => {
        if (input.files && input.files[0]) {
            const fileSize = input.files[0].size;
            totalSize += fileSize;
            
            if (fileSize > maxSizePerFile) {
                alert(`La photo ${index + 1} dépasse la limite de 2 Mo.`);
                isValid = false;
            }
        }
    });

    if (totalSize > maxTotalSize) {
        alert(`Le poids total des images (${(totalSize / (1024 * 1024)).toFixed(2)} Mo) dépasse la limite autorisée de 10 Mo.`);
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();
        return;
    }

    // Afficher l'état de chargement sur le bouton
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<span class="flex items-center gap-2">... Publication en cours...</span>';
});
```

## 4. Controller Implementations

### `app/Http/Controllers/ProduitController.php`
Replace old `$file->store('produits', 'public')` logic:
```php
use App\Helpers\ImageHelper;

// In store() or update() method:
if ($request->hasFile('images')) {
    $images = [];
    foreach ($request->file('images') as $file) {
        $images[] = ImageHelper::compressAndStore($file, 'produits');
    }
    // save $images to model
}
```

### `app/Http/Controllers/AuthController.php` & `DashController.php`
```php
use App\Helpers\ImageHelper;

// Inside Profile update / Registration logic:
if ($request->hasFile('pfp')) {
    $user->pfp = ImageHelper::compressAndStore($request->file('pfp'), 'profiles'); // or 'pfps'
}
```

## 5. Filament Admin Sorting

To ensure new items appear first in the admin panel by default, add `->defaultSort('created_at', 'desc')` to every table definition.

### Example: `app/Filament/Resources/Users/Tables/UsersTable.php`
```php
<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                // Columns layout
            ]);
    }
}
```
*(Apply the exact same `defaultSort` to `ProduitsTable`, `CategoriesTable`, `VillesTable`, and `SignalementProduitsTable`)*.
