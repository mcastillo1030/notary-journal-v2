<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/people', [PersonController::class, 'index'])->name('people.index');
    Route::get('/people-list', [PersonController::class, 'list'])->name('people.list');
    Route::get('/people/create', [PersonController::class, 'create'])->name('people.create');
    Route::post('/people', [PersonController::class, 'storeAjax'])->name('people.storeAjax');
    Route::get('/people/{person}', [PersonController::class, 'show'])->name('people.show');
    Route::patch('/people/{person}', [PersonController::class, 'updateAjax'])->name('people.updateAjax');
    Route::patch('/people/{person}/attach', [PersonController::class, 'attach'])->name('people.attach');
    Route::patch('/people/{person}/detach', [PersonController::class, 'detach'])->name('people.detach');
    Route::delete('/people/{person}', [PersonController::class, 'destroyAjax'])->name('people.destroyAjax');

    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::patch('/notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::get('/address-list', [AddressController::class, 'list'])->name('addresses.list');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('/addresses/{address}', [AddressController::class, 'show'])->name('addresses.show');
    Route::patch('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::patch('/addresses/{address}/attach', [AddressController::class, 'attach'])->name('addresses.attach');
    Route::patch('/addresses/{address}/detach', [AddressController::class, 'detach'])->name('addresses.detach');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
});

require __DIR__.'/auth.php';
