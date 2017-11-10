<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Thumbnails Base Path
    |--------------------------------------------------------------------------
    | This is the path where you put all the images of the web app. And make sure
    | you don't include this part of path to images when they are saved to db or anywhere.
    | Example:
    | Image full path: /uploads/images/posts/authors/author.jpg (in public directory)
    | Base path: /uploads/images
    | Other part of the path: posts/authors/author.jpg
    |--------------------------------------------------------------------------
    |
    */

    'base_path' => "/assets/images",

    /*
    |--------------------------------------------------------------------------
    | Thumbnails Directory Name
    |--------------------------------------------------------------------------
    |
    | The name of the main directory for the thumbnails.
    |
    */

    'thumbs_dir_name' => 'thumbs',

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',

    /*
    |--------------------------------------------------------------------------
    | Image Placeholder URL
    |--------------------------------------------------------------------------
    |
    | In case there will be missing any image file will be generated a default image from the url below.
    | Example: http://via.placeholder.com/350x150/fff/111
    | {width} and {height} vars will be filled dynamically
    */

    'placeholder' => 'http://via.placeholder.com/{width}x{height}/{bgColor}',

    /*
    |--------------------------------------------------------------------------
    | Error Image (Optional)
    |--------------------------------------------------------------------------
    |
    | If you want to show an error image instead of a placeholder image you can put the small path here
    | Do not include base_path here
    | Example: /errors/no-image.png
    |
    */

    'error_image' => ''

];
