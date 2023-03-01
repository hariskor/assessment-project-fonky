<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\HomeController;

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
Route::get('/login', function () {return view('auth.login'); });
// Route::post('/login', [LoginController::class,'login']);
Auth::routes();


Route::get('seller/locations/{seller}', [LocationController::class, 'findBySeller'])->name('seller-locations');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::group(['prefix' => '/buyer/'], function () {
        Route::get('/', [BuyerController::class, 'index']);
        Route::get('/my-purchases', [BuyerController::class, 'index'])->name('buyer-index');
    });

    Route::middleware(['check.seller'])->group(function () {
        Route::group(['prefix' => '/seller'], function () {
            Route::get('/sales', [SellerController::class, 'mySales'])->name('seller-my-sales');
            Route::get('/my-locations', [SellerController::class, 'myLocations'])->name('seller-my-locations');
            Route::get('/my-customers', [SellerController::class, 'myCustomers'])->name('seller-customers');
            Route::get('/customer/{user}', [SellerController::class, 'customerPurchaseHistory'])->name('seller-customer-history');
            Route::get('/location/{location}', [SellerController::class, 'locationOrders'])->name('seller-location-orders');
        });
    });
});
