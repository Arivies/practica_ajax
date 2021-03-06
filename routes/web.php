<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Generos\GeneroController;
use App\Http\Controllers\Bandas\BandaController;
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

Route::group(['middleware' => ['cors']], function () {

Route::get('/', function () {
    return view('layout');
})->name('index');

Route::resource('/generos',GeneroController::class)->names('generos');
Route::resource('/bandas',BandaController::class)->names('bandas');

});
