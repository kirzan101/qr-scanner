<?php

use App\Http\Controllers\API\DepartmentApiController;
use App\Http\Controllers\API\ScanAPIController;
use App\Http\Controllers\API\ScanProfileApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// //profile api
Route::get('/profiles', [ScanProfileApiController::class, 'index']);
Route::get('/departments', [DepartmentApiController::class, 'index']);


