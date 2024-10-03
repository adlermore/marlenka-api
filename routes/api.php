<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


/*Route::middleware(['lang'])->group(function () {
});*/

Route::group(['prefix' => '/', 'middleware' => ['blockcors','apiLogger']], function () {

    Route::post('register', [AuthController::class, 'register']);

    Route::post('login', [AuthController::class, 'login']);

    Route::post('logout', [AuthController::class, 'logout']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::group(['prefix' => '/admin', 'middleware' => ['isAdmin']], function () {


        });

        Route::post('addToCart', [HomeController::class, 'addToCart']);

        Route::get('getUserCart', [HomeController::class, 'getUserCart']);

        Route::post('addToWishList', [HomeController::class, 'addToWishList']);

        Route::get('getUserWishList', [HomeController::class, 'getUserWishList']);

        Route::post('storeProfile', [HomeController::class, 'storeProfile']);

        Route::get('getProfile', [HomeController::class, 'getProfile']);

        Route::post('storeOrder', [OrderController::class, 'storeOrder']);

        Route::get('getOrders', [OrderController::class, 'getOrders']);


    });

    Route::get('getCategories', [HomeController::class, 'getCategories']);

    Route::get('getHomeData', [HomeController::class, 'getHomeData']);

    Route::post('subscribe', [HomeController::class, 'subscribe']);

    Route::get('getProducts', [ProductController::class, 'getProducts']);


});



