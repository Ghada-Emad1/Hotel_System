<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait ImageUploadTrait
{
    /**
     * Handle the image upload process.
     *
     * @param UploadedFile|null $image
     * @param string $folder
     * @param string|null $existingImage
     * @param string $defaultImage
     * @return string
     */
    public function uploadImage(?UploadedFile $image, string $folder, ?string $existingImage = null, string $defaultImage = 'avatar.png'): string
    {
        if (!$image) {
            return $existingImage ?? $defaultImage;
        }

        // Delete the old image if it exists and isn't the default
        if ($existingImage && $existingImage !== $defaultImage) {
            Storage::disk('public')->delete("$folder/$existingImage");
        }

        // Store the new image
        $filename = time() . '_' . $image->getClientOriginalName();
        $image->storeAs($folder, $filename, 'public');

        return $filename;
    }

    /**
     * Delete an image from storage.
     *
     * @param string|null $imagePath
     * @param string $folder
     * @param string $defaultImage
     * @return void
     */
    public function deleteImage(?string $imagePath, string $folder, string $defaultImage = 'avatar.png'): void
    {
        if ($imagePath && $imagePath !== $defaultImage) {
            Storage::disk('public')->delete("$folder/$imagePath");
        }
    }
}
