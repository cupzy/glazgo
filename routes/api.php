<?php

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return Response::json([
        'message' => 'Hello, world',
    ]);
});
