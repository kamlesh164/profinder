<?php

use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\SpAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\API\Vendor\AuthController as VendorAuthController;
use App\Http\Controllers\API\MasterController;
use App\Http\Controllers\API\ProductController as CustomerProductController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\Vendor\BidController;
use App\Http\Controllers\API\Vendor\KYCController as VendorKycController;
use App\Http\Controllers\API\Vendor\ProductController;
use App\Http\Controllers\API\Vendor\SubscriptionController;

Route::get('/states', [MasterController::class, 'states']);
Route::get('/cities', [MasterController::class, 'cities']);
Route::get('/designation', [MasterController::class, 'designation']);

Route::post('auth/send-otp',[AuthController::class,'generateOTP']);
Route::post('auth/verify-otp',[AuthController::class,'otpVerify']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/chat/start', [ChatController::class, 'start']);
    Route::get('/chat/{id}', [ChatController::class, 'messages']);
    Route::post('/chat/message', [ChatController::class, 'sendMessage']);
});

//Customers API
Route::prefix('customer')->group(function () {
    Route::post('/signup', [AuthController::class, 'register']);
    Route::post('/signin', [AuthController::class, 'login']);
    Route::post('/verify-phone-otp', [AuthController::class, 'VerifyOtp']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/categories', [MasterController::class, 'category']);
        Route::get('/subcategories', [MasterController::class, 'subcategory']);
        Route::get('/timeslots', [MasterController::class, 'timeslots']);
        Route::get('/apartments', [MasterController::class, 'apartments']);

        //Address
        Route::prefix('address')->group(function () {
            Route::get('/list', [AddressController::class, 'index']);
            Route::get('/delete/{id}', [AddressController::class, 'destroy']);
            Route::post('/store', [AddressController::class, 'store']);
            Route::post('/update', [AddressController::class, 'update']);
        });
        //Product
        Route::get('product/list', [CustomerProductController::class, 'index']);

        //Wishlist
        Route::get('product/wishlist/all', [CustomerProductController::class, 'AllWishlist']);
        Route::post('product/wishlist/store', [CustomerProductController::class, 'wishlistStore']);
        Route::get('product/wishlist/{id}', [CustomerProductController::class, 'wishlistRemove']);

        //cart
        Route::prefix('cart')->group(function () {
            Route::get('/', [CartController::class, 'index']);
            Route::post('/add', [CartController::class, 'addToCart']);
            Route::post('/update/{rowId}', [CartController::class, 'update']);
            Route::get('/remove/{rowId}', [CartController::class, 'remove']);
            Route::get('/clear', [CartController::class, 'clear']);
        });

        //Projects
        Route::get('projects', [ProjectController::class, 'index']);
        Route::post('projects/store', [ProjectController::class, 'store']);
        Route::get('/bids-list', [ProjectController::class, 'allbids']);
        Route::post('/approve-bid', [ProjectController::class, 'ApproveBid']);

        //Bookings
        Route::get('bookings', [BookingController::class, 'index']);
        Route::post('bookings/store', [BookingController::class, 'store']);

    });
});

//End Customer API

//Vendor API
Route::prefix('vendor')->group(function () {
    Route::post('/signup', [VendorAuthController::class, 'signup']);
    Route::post('/signin', [VendorAuthController::class, 'signin']);
    Route::post('/verify-phone-otp', [VendorAuthController::class, 'VerifyOtp']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/my-profile', [VendorAuthController::class, 'profile']);
        Route::get('/logout', [VendorAuthController::class, 'logout']);

        Route::post('/store-kyc', [VendorKycController::class, 'storeKyc']);
        Route::get('/kyc-details', [VendorKycController::class, 'Kycdetails']);

        Route::get('/product/list', [ProductController::class, 'index']);
        Route::post('/product/store', [ProductController::class, 'store']);
        Route::get('/product/details/{id}', [ProductController::class, 'show']);
        Route::post('/product/update', [ProductController::class, 'update']);
        Route::get('/product/update/status/{id}', [ProductController::class, 'changeStatus']);

         //Contractor
         Route::post('/place-bid', [BidController::class, 'placeBid']);
         Route::get('/project-list', [BidController::class, 'allprojects']);
         Route::get('/bids-list', [BidController::class, 'allbids']);

         //Subscription Module
         Route::post('/buy-subscription', [SubscriptionController::class, 'buySubscription']);
         Route::get('/subscription-details', [SubscriptionController::class, 'subscriptionDetail']);
         Route::get('/subscription/purchase-history', [SubscriptionController::class, 'SubscriptionPurchaseHistory']);
    });
});
//End Vendor API

// SP API
Route::prefix('sp')->group(function () {
    Route::post('/signup', [SpAuthController::class, 'signup']);
    Route::post('/signin', [SpAuthController::class, 'signin']);
});
// END SP API
