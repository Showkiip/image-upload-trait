<?php

return [
    'disk' => env('IMAGE_UPLOAD_DISK', 'public'),
    'allowed_types' => ['jpg', 'jpeg', 'png', 'gif'],
    'max_size' => 2048, // Size in KB
];