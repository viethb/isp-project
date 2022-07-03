<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\BoardController;
use App\Http\Controllers\TaskController;

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
    Route::post('/board/add', [BoardController::class, "addBoard"])->name("addBoard");
    Route::post('/board/{key}/update', [BoardController::class, "updateBoard"])->name("updateBoard");
    Route::get('/board/{key}/delete', [BoardController::class, "deleteBoard"])->name("deleteBoard");

    Route::post('/board/{key}/task/add', [TaskController::class, "addTask"])->name("addTask");
    Route::post('/board/{key}/task/edit', [TaskController::class, "editTask"])->name("editTask");
});

Route::get('/board/{key?}', [BoardController::class, "show"])->name("showBoard");
Route::post('/board/{key}/task/update', [TaskController::class, "updateTask"])->name("updateTask");
Route::get("/board/{key}/task/{id}/updatestatus/{status}", [TaskController::class, "updateStatus"])->name("updateStatus");
