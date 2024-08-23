<?php

namespace Showkiip\ImageUploadTrait\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait ImageUpload
{
    public function uploads($file, $path, $existingFile = null)
    {
        try {

            if ($existingFile) {
                Storage::disk('public')->delete($existingFile);
            }

            $unqRan = Str::random(20);
            // Check if the file is a valid file path or an uploaded file
            if (is_string($file)) {
                // Handling the case when `$file` is a file path (as in extracted files)
                $fileName = basename($file);
                $destinationPath = $path .$unqRan . $fileName;
                Storage::disk('public')->put($destinationPath, file_get_contents($file));

                return [
                    'fileName' => $fileName,
                    'fileType' => pathinfo($fileName, PATHINFO_EXTENSION),
                    'filePath' => $destinationPath,
                    'fileSize' => filesize($file)
                ];
                
            } else if ($file && $file->isValid()) {

                $fileName = time() . $unqRan . $file->getClientOriginalName();
                Storage::disk('public')->putFileAs($path, $file, $fileName);

                return [
                    'fileName' => $file->getClientOriginalName(),
                    'fileType' => $file->getClientOriginalExtension(),
                    'filePath' => $path . $fileName,
                    'fileSize' => $this->fileSize($file)
                ];
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function fileSize($file, $precision = 2)
    {
        $size = $file->getSize();

        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }

        return $size;
    }
}
