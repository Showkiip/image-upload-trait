<?php

namespace Showkiip\ImageUploadTrait;

use Illuminate\Support\ServiceProvider;

class ImageUploadServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge package configuration with application's copy
        $this->mergeConfigFrom(__DIR__ . '/../config/image-upload.php', 'image-upload');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish configuration file
        $this->publishes([
            __DIR__ . '/../config/image-upload.php' => config_path('image-upload.php'),
        ], 'config');
    }
}
