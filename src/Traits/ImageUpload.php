namespace Showkiip\ImageUploadTrait\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait ImageUpload
{
    public function uploads($file, $path, $existingFile = null)
    {
        try {
            $disk = config('image-upload.disk');
            $allowedTypes = config('image-upload.allowed_types');
            $maxSize = config('image-upload.max_size') * 1024; // Convert to bytes
            if ($existingFile) {
                Storage::disk($disk)->delete($existingFile);
            }
            if ($file && $file->isValid()) {
                if (!in_array($file->getClientOriginalExtension(), $allowedTypes)) {
                    return ['error' => 'File type not allowed'];
                }
                if ($file->getSize() > $maxSize) {
                    return ['error' => 'File size exceeds limit'];
                }
                $unqRan = Str::random(20);
                $fileName = time() . $unqRan . $file->getClientOriginalName();
                Storage::disk($disk)->putFileAs($path, $file, $fileName);

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