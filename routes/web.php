<?php

use App\Http\Controllers\Collectible\CategoryController;
use App\Http\Controllers\Collectible\CollectibleController;
use App\Http\Controllers\Collectible\ItemController;
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

Route::get('/', fn () => view('home'))->name('home');

require __DIR__.'/auth.php';

Route::resource('collectibles', CollectibleController::class);
Route::resource('categories', CategoryController::class);
Route::resource('items', ItemController::class);
