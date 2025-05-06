<?php

use App\Http\Controllers\EndpointController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrafficController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/subscriptions', function () {
    return view('subscriptions');
});

Route::get('/dashboard', [EndpointController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('throttle:30,1')->group([function () {
    Route::any('/serve/{endpoint}', [EndpointController::class, 'show'])->name('traffic.serve');
}]);

Route::middleware('auth')->group(function () {
    Route::get('/endpoints/create', [EndpointController::class, 'create'])->name('endpoints.create');
    Route::post('/endpoints', [EndpointController::class, 'store'])->name('endpoints.store');
    Route::delete('/endpoints/{endpoint}', [EndpointController::class, 'delete'])->name('endpoints.destroy');
    Route::get('/endpoints/{endpoint}/download', [EndpointController::class, 'download'])->name('endpoints.download');

});

require __DIR__.'/auth.php';
