<?php

use App\Http\Actions\GetPostsAction;
use Illuminate\Support\Facades\Route;

Route::post('/posts', GetPostsAction::class);
