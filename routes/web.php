<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KadoController;
use App\Http\Controllers\ProjectController;

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
    return view('welcome');
});

Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('login');
Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback']);

Route::get('/home', [LoginController::class, 'home'])->name('home');

Route::group(['prefix' => 'kado', 'middleware' => 'auth'], function(){
    Route::get('/{user_id}/{term_id}', [KadoController::class, 'list'])->name('kado.list');
    Route::get('/all/{year}/{month}', [KadoController::class, 'listAll'])->name('kado.list_all');
    Route::get('/{user_id}/{year}/{month}', [KadoController::class, 'listDetail'])->name('kado.list_detail');
});

Route::group(['prefix' => 'projects', 'middleware' => 'auth'], function(){
    Route::get('index', [ProjectController::class, 'index'])->name('project.index');
    Route::get('create', [ProjectController::class, 'create'])->name('project.create');
    Route::post('store', [ProjectController::class, 'store'])->name('project.store');
    Route::get('show/{project_id}', [ProjectController::class, 'show'])->name('project.show');
    Route::get('edit/{project_id}', [ProjectController::class, 'edit'])->name('project.edit');
    Route::post('update/{project_id}', [ProjectController::class, 'update'])->name('project.update');
    Route::post('destroy/{project_id}', [ProjectController::class, 'destroy'])->name('project.destroy');
});
