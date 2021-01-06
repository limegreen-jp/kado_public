<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KadoController;
use App\Http\Controllers\ProjectController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/hello', function () {
    $message = 'Hello';

    return response()->json([
        'message' => $message
    ]);
});

Route::get('/chart/{user_id}/{term_id}', [KadoController::class, 'setChartApi'])->name('api.chart');
Route::get('/chart/all/{year}/{month}', [KadoController::class, 'setAllChartApi'])->name('api.chart_all');
Route::get('/chart/{user_id}/{year}/{month}', [KadoController::class, 'setDetailChartApi'])->name('api.chart_detail');

Route::get('/project/skill_level', [ProjectController::class, 'levelSkillApi'])->name('api.skill_level');

