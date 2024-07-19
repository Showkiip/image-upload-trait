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
            if ($file && $file->isValid()) {
                $unqRan = Str::random(20);
                $fileName = time() . $unqRan . $file->getClientOriginalName();
                Storage::disk('public')->putFileAs($path, $file, $fileName);
                $file_name = $file->getClientOriginalName();
                $file_type = $file->getClientOriginalExtension();
                $filePath = $path . $fileName;

                return [
                    'fileName' => $file_name,
                    'fileType' => $file_type,
                    'filePath' => $filePath,
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