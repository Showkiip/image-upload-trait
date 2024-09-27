<?php

namespace Showkiip\ImageUploadTrait\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

trait ImageUpload
{
    /**
     * Upload a file (either from path or uploaded instance).
     *
     * @param mixed $file
     * @param string $path
     * @param string|null $existingFile
     * @return array|string
     */
    public function uploads($file, $path, $existingFile = null)
    {
        try {
            // Delete existing file if provided
            $this->deleteExistingFile($existingFile);

            // Ensure directory exists
            $this->ensureDirectoryExists($path);

            // Handle either a file path or an uploaded file
            if (is_string($file)) {
                return $this->uploadFromPath($file, $path);
            } elseif ($file && $file->isValid()) {
                return $this->uploadFromUploadedFile($file, $path);
            } else {
                throw new \Exception('Invalid file provided.');
            }
        } catch (\Exception $e) {
            // Log error and return a generic error message
            Log::error('File upload error: ' . $e->getMessage());
            return ['error' => 'An error occurred during file upload.'];
        }
    }

    /**
     * Delete the existing file if it exists.
     *
     * @param string|null $existingFile
     * @return void
     */
    protected function deleteExistingFile($existingFile = null)
    {
        if ($existingFile && Storage::disk('public')->exists($existingFile)) {
            Storage::disk('public')->delete($existingFile);
        }
    }

    /**
     * Ensure the directory exists.
     *
     * @param string $path
     * @return void
     */
    protected function ensureDirectoryExists($path)
    {
        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }
    }

    /**
     * Handle uploading a file from a file path.
     *
     * @param string $filePath
     * @param string $path
     * @return array
     */
    protected function uploadFromPath($filePath, $path)
    {
        $fileName = uniqid() . basename($filePath);
        $destinationPath = $path . $fileName;

        Storage::disk('public')->put($destinationPath, file_get_contents($filePath));

        return [
            'fileName' => basename($filePath),
            'fileType' => pathinfo($filePath, PATHINFO_EXTENSION),
            'filePath' => $destinationPath,
            'fileSize' => filesize($filePath),
        ];
    }

    /**
     * Handle uploading an instance of an uploaded file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @return array
     */
    protected function uploadFromUploadedFile($file, $path)
    {
        // Validate if the file is an image
        $this->validateImageFile($file);

        $fileName = uniqid() . '_' . $file->getClientOriginalName();
        $destinationPath = $path . $fileName;

        // Save the uploaded file
        Storage::disk('public')->putFileAs($path, $file, $fileName);

        return [
            'fileName' => $file->getClientOriginalName(),
            'fileType' => $file->getClientOriginalExtension(),
            'filePath' => $destinationPath,
            'fileSize' => $this->formatFileSize($file->getSize()),
        ];
    }

    /**
     * Validate if the uploaded file is an image.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return void
     * @throws \Exception
     */
    protected function validateImageFile($file)
    {
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp'];
        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            throw new \Exception('Invalid file type. Only image files are allowed.');
        }
    }

    /**
     * Format file size to a human-readable format.
     *
     * @param int $size
     * @param int $precision
     * @return string
     */
    protected function formatFileSize($size, $precision = 2)
    {
        if ($size <= 0) {
            return '0 bytes';
        }

        $base = log($size, 1024);
        $suffixes = ['bytes', 'KB', 'MB', 'GB', 'TB'];

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
}
