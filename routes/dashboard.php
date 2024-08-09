<?php

use App\Http\Controllers\Dashboard\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('dashboard')->group(function (){




    Route::apiResources([
        // Create Categories Route
        'categories' => CategoryController::class,

    ]);



});
