<?php

use App\Http\Controllers\Collectible\CategoriesController;
use App\Http\Controllers\Collectible\CollectibleController;
use App\Http\Controllers\Collectible\ItemsController;
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

Route::get('/collectibles/{collectible}', [CategoriesController::class, 'view'])
     ->name('collectibles.categories');
Route::get('/collectibles/{collectible}/{category}', [ItemsController::class, 'view'])
    ->name('collectibles.items');
Route::get('/collectibles/{collectible}/{category}/{item}', [ItemsController::class, 'single'])
    ->name('collectibles.item');
