<?php

use App\Http\Controllers\ScanController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
