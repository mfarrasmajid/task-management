<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
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

Route::get('/', function(){ return redirect()->route('tasks.index'); });
Auth::routes();
Route::middleware('auth')->group(function(){
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('tasks', TaskController::class);
});