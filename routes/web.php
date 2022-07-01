<?php

use Illuminate\Support\Facades\Route;

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
    return view('root');
})->name('root');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/welcome', function () {
        return view('livewire.welcome');
    })->name('livewire-welcome');
    Route::post('/board/add', [\App\Http\Livewire\BoardController::class, "addBoard"])->name("addBoard");
    Route::post('/board/{key}/task/add', [\App\Http\Controllers\TaskController::class, "addTask"])->name("addTask");
    Route::post('/board/{key}/task/update', [\App\Http\Controllers\TaskController::class, "updateTask"])->name("updateTask");
});

Route::get('/board/{key?}', [\App\Http\Livewire\BoardController::class, "show"])->name("showBoard");
