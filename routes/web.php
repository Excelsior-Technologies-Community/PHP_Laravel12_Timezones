<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\TimezoneDemoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('events.index');
});

Route::resource('events', EventController::class);
Route::get('/timezone-demo', [TimezoneDemoController::class, 'demo'])->name('timezone.demo');
Route::post('/set-timezone', [TimezoneDemoController::class, 'setTimezone'])->name('set.timezone');

Route::get('/test', function () {
    // Use collect() to create a collection instead of an array
    $events = collect([]); // This creates an empty collection
    $currentTimezone = 'Asia/Kolkata';
    $storageTimezone = 'UTC';
    
    return view('events.index', compact('events', 'currentTimezone', 'storageTimezone'));
});


Route::get('/simple-test', function () {
    return view('simple-test');
});