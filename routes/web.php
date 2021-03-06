<?php

use App\Models\Genre;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoviesController;

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

Route::get('/', [MoviesController::class, 'index'])->name('movies.index');
Route::get('/movie/{video}', [MoviesController::class, 'show'])->name('movies.show');
Route::get('/movie/create/{kinopoiskId}', [MoviesController::class, 'create'])->name('movies.create');