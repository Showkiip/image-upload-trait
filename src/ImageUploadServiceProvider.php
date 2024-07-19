<?php

namespace Showkiip\ImageUploadTrait;

use Illuminate\Support\ServiceProvider;

class ImageUploadServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/image-upload.php' => config_path('image-upload.php'),
        ], 'config');

        // Merge package configuration with application's copy
        $this->mergeConfigFrom(__DIR__.'/../config/image-upload.php', 'image-upload');
    }

    public function register()
    {
        // Register bindings, if any
    }
}
