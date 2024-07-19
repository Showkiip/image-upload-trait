<?php

namespace Showkiip\ImageUploadTrait;

use Illuminate\Support\ServiceProvider;

class ImageUploadServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/image-upload.php' => config_path('image-upload.php'),
        ], 'config');
    }

    public function register()
    {
        // Register bindings, if any
    }
}
