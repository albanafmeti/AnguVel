<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix("v1")->group(function () {
    Route::get('posts', 'Api\PostController@index');
    Route::get('posts/{post}', 'Api\PostController@get');
    Route::get('posts/{post}/comments', 'Api\PostController@comments');
    Route::post('posts/{post}/comments/add', 'Api\PostController@addComment');
    Route::get('posts/latest/{limit}', 'Api\PostController@latest');
    Route::get('posts/{post}/alternatives', 'Api\PostController@alternatives');
    Route::get('categories', 'Api\CategoryController@index');
    Route::get('categories/{category}', 'Api\CategoryController@get');
    Route::get('categories/{category}/posts', 'Api\CategoryController@posts');
    Route::post('subscribe', 'Api\SubscriberController@subscribe');

    Route::middleware('auth:api')->group(function () {
        Route::post('categories/store', 'Api\CategoryController@store');
        Route::put('categories/{category}/update', 'Api\CategoryController@update');
        Route::delete('categories/{category}/delete', 'Api\CategoryController@delete');
        Route::post('posts/store', 'Api\PostController@store');
        Route::post('posts/{post}/update', 'Api\PostController@update');
        Route::delete('posts/{post}/delete', 'Api\PostController@delete');

        Route::post('froala/image/delete', 'Api\FroalaController@imageDelete');
    });

    Route::prefix("froala")->group(function () {
        Route::post('file/upload', 'Api\FroalaController@imageUpload');
        Route::post('image/upload', 'Api\FroalaController@imageUpload');
        Route::get('image/manager/load', 'Api\FroalaController@imageManagerLoad');
        Route::post('image/manager/delete', 'Api\FroalaController@imageManagerDelete');
    });
});
