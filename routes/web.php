<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChildrenController;
use App\Http\Controllers\Auth\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});


Route::middleware('auth')->group(function () {
    Route::get('/socios', [UserController::class, 'index'])->name('socios');
    Route::get('/socios/{user}', [UserController::class, 'edit'])->name('user.edit');    
    Route::patch('/socios/{user}/edit', [UserController::class, 'update'])->name('user.update');
    Route::patch('/socios/{user}/adress', [UserController::class, 'adress'])->name('user.adress');
    Route::get('/nuevo_socio', [UserController::class, 'create'])->name('socios.create');
    Route::patch('/nuevo_socio/guardar', [UserController::class, 'store'])->name('socios.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile', [ProfileController::class, 'updateAdress'])->name('adress.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/niños', [ChildrenController::class, 'index'])->name('niños');
    Route::get('/niños/{nino}', [ChildrenController::class, 'edit'])->name('niños.edit');    
    Route::patch('/niños/{nino}/edit', [ChildrenController::class, 'update'])->name('niños.update');
    Route::get('/nuevo_niño', [ChildrenController::class, 'create'])->name('niños.create');
    Route::patch('/nuevo_niño/guardar', [ChildrenController::class, 'store'])->name('niños.store');
});

require __DIR__.'/auth.php';
