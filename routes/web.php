<?php

use App\Http\Controllers\Collectible\CategoryController;
use App\Http\Controllers\Collectible\CollectibleController;
use App\Http\Controllers\Collectible\ItemController;
use App\Http\Controllers\Collectible\SearchController;
use App\Http\Controllers\Collection\CollectionController;
use App\Http\Controllers\Collection\GoalController;
use App\Http\Controllers\Collection\StockController;
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

Route::any('/collectibles/{collectible}/search', [SearchController::class, 'search'])
     ->name('collectibles.search');
Route::get('/collections/{collection}/stock', [StockController::class, 'index'])
     ->name('collections.stock');

Route::resource('collectibles', CollectibleController::class);
Route::resource('collectibles.categories', CategoryController::class);
Route::resource('collectibles.categories.items', ItemController::class);
Route::resource('collections', CollectionController::class);
Route::resource('collections.goals', GoalController::class);
