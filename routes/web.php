<?php

use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', function () {
    return redirect('/dashboard' );
});


Route::group(['prefix' => 'dashboard', 'middleware' => ['authWeb','isAdmin']], function () {

    Route::resource('/', DashboardController::class)->names('dashboard');

    Route::get('abort403', [DashboardController::class, 'abort403'])->name('abort403');

    Route::resource('contact_us', ContactUsController::class)->names('contact_us');

    Route::resource('home_slider', ContactUsController::class)->names('home_slider');

});


Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
