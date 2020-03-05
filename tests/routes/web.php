<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Arni\CustomSort\Tests\Controllers')->group(function () {
    Route::get('/posts', 'PostsController@index');
    Route::put('/posts/updateCustomSort', 'PostsController@updateCustomSort');
});
