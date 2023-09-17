<?php

use App\Controllers\HomeController;
use App\Routing\Route;

return [
    Route::get('/', [HomeController::class, 'index']),
    Route::get('/test', [HomeController::class, 'test'])
];
