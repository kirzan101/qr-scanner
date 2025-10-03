<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\DepartmentController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/home', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/scanned', function () {
    return Inertia::render('ScannedHistory');
})->name('scanned');

// Route::get('/scan', function () {
//     return Inertia::render('Scans');
// })->name('scan');

Route::resource('scans', ScanController::class)->except(['create', 'edit', 'show']);
Route::post('/scans', [ScanController::class, 'scan'])->name('scan');
//profile
Route::resource('profiles', ProfileController::class)->except(['create', 'edit', 'show']);
Route::resource('departments', DepartmentController::class)->except(['create', 'edit', 'show']);
Route::resource('properties', PropertyController::class)->except(['create', 'edit', 'show']);
Route::resource('locations', LocationController::class)->except(['create', 'edit', 'show']);
