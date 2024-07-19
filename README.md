# Image Upload Trait

A simple trait for handling image uploads in Laravel.

## Installation

### Step 1: Install the Package

Add the package to your Laravel project using Composer:



 ```bash
composer require showkiip/image-upload-trait
```
### Step 2: Publish the Configuration File

Publish the configuration file to customize the default settings:

```bash
php artisan vendor:publish --provider="Showkiip\ImageUploadTrait\ImageUploadServiceProvider"
```

This command will create a `config/image-upload.php` file in your Laravel project.

### Step 3: Create a Symbolic Link

To make uploaded files accessible via the web, create a symbolic link from the `public/storage` directory to the `storage/app/public` directory:

```bash
php artisan storage:link
```
This command creates the necessary link for serving files stored in `storage/app/public`.

## Configuration

Edit the `config/image-upload.php` file to customize the following settings:

disk: The storage disk to use (default: public).
allowed_types: An array of allowed file extensions (default: ['jpg', 'jpeg', 'png', 'gif']).
max_size: Maximum file size in kilobytes (default: 2048 KB).

## Example configuration:


```bash
return [
    'disk' => env('IMAGE_UPLOAD_DISK', 'public'),
    'path' => env('IMAGE_UPLOAD_PATH', 'uploads/images/'),
    'allowed_types' => ['jpg', 'jpeg', 'png', 'gif'],
    'max_size' => 2048, // Size in KB
];
```

### Usage

In your controller, use the `ImageUpload` trait to handle file uploads:

```bash
use Showkiip\ImageUploadTrait\Traits\ImageUpload;

class SomeController extends Controller
{
    use ImageUpload;

    public function upload(Request $request)
    {
        $result = $this->uploads($request->file('image'));
        
        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 400);
        }

        return response()->json($result);
    }
}

```
## Common Issues

Files Not Accessible: Ensure you have run `php artisan storage:link` to create the symbolic link from `public/storage` to storage/app/public. Without this, files stored in storage/app/public will not be accessible via the web.

## License

This package is open-source and available under the MIT License.




