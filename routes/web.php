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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::POST('/start', [App\Http\Controllers\HomeController::class,'start'])->name('start');
Route::get('/start', [App\Http\Controllers\HomeController::class,'start'])->name('start');

// 勤務終了
Route::post('/end', [App\Http\Controllers\HomeController::class,'end'])->name('end');
// 休憩開始
Route::post('/rest_start', [App\Http\Controllers\HomeController::class,'rest_start'])->name('rest_start');
// 休憩終了
Route::post('/rest_end', [App\Http\Controllers\HomeController::class,'rest_end'])->name('rest_end');
//　日付一覧
Route::get('/confilm', [App\Http\Controllers\HomeController::class, 'confilm'])->name('confilm')->name('confilm');