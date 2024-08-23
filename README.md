# Image Upload Trait

A simple trait for handling image uploads in Laravel.

## Installation

### Step 1: Install the Package
before install the package in your laravel applications :
By default, Composer pulls in packages from Packagist so you’ll have to make a slight adjustment to your new project composer.json file. Open the file and update include the following array somewhere in the object:
```bash
 "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/Showkiip/image-upload-trait.git"
        }
    ],
```
Add the package to your Laravel project using Composer:



 ```bash
composer require showkiip/image-upload-trait
```


### Step 2: Create a Symbolic Link

To make uploaded files accessible via the web, create a symbolic link from the `public/storage` directory to the `storage/app/public` directory:

```bash
php artisan storage:link
```
This command creates the necessary link for serving files stored in `storage/app/public`.



### Usage

In your controller, use the `ImageUpload` trait to handle file uploads:

```bash
use Showkiip\ImageUploadTrait\Traits\ImageUpload;

class SomeController extends Controller
{
    use ImageUpload;

    public function upload(Request $request)
    {
         $file = $request->file('image');
        $path = 'uploads/images';

        // Existing file path to delete (optional)
        $existingFile = 'uploads/images/old_example.jpg';

        $uploadData = $this->uploads($file, $path, $existingFile);
        return response()->json(['data' => $uploadData], 200);
    }
}

```

## Example Response
When the `uploadImage` method is called and a file is successfully uploaded, the JSON response might look like this:


```bash
{
    "data": {
        "fileName": "example.jpg",
        "fileType": "jpg",
        "filePath": "uploads/images/1626800000_randomstring_example.jpg",
        "fileSize": "1.5 MB"
    }
}

```
Handling File Path as a String
If $file is a string representing a file path, the following code handles the upload process:

If an error occurs, the response might look like this:
``` bash
{
    "error": "The file is not valid."
}
```


## Common Issues

Files Not Accessible: Ensure you have run `php artisan storage:link` to create the symbolic link from `public/storage` to storage/app/public. Without this, files stored in storage/app/public will not be accessible via the web.

## License

This package is open-source and available under the MIT License.




