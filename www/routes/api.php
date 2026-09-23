<?php

use App\Http\Controllers\admin\ServiceCategoryController;
use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\SpAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\API\Vendor\AuthController as VendorAuthController;
use App\Http\Controllers\API\MasterController;
use App\Http\Controllers\API\ApiProductController;
use App\Http\Controllers\API\Vendor\BidController;
use App\Http\Controllers\API\Vendor\KYCController as VendorKycController;
use App\Http\Controllers\API\Vendor\ProductController;
use App\Http\Controllers\API\Vendor\SubscriptionController;
use App\Http\Controllers\API\ApiServiceCategoryController;
use App\Http\Controllers\API\ApiServiceController;
use App\Http\Controllers\API\ApiServiceProfessionalBookingController;
use App\Http\Controllers\API\ApiServiceRatingController;
use App\Http\Controllers\API\ApiSubscriptionPlanController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\ApiJobPostController;
use App\Http\Controllers\PaymentController;

Route::get('/states', [MasterController::class, 'states']);
Route::get('/states/{stateId}/cities', [MasterController::class, 'citiesByState']);
Route::get('/cities/search', [MasterController::class, 'searchCity']);
Route::get('/cities-with-services', [MasterController::class, 'citiesWithServices']);
Route::get('/search', [MasterController::class, 'search']);
Route::get('/banners', [MasterController::class, 'getBanners']);
Route::get('/reviews', [ApiServiceRatingController::class, 'getCustomerReviews']);
Route::get('/categories', [ApiServiceCategoryController::class, 'allCategory']);
Route::get('/product/categories', [MasterController::class, 'category']);
Route::get('/job-post/list', [ApiJobPostController::class, 'getAllJobPost']);
Route::get('/job-post/details', [ApiJobPostController::class, 'jobDetailsById']);
Route::get('/plan', [ApiSubscriptionPlanController::class, 'getAllPlans']);

Route::post('/payment/verify', [PaymentController::class, 'verifyRazorpayPayment']);
Route::post('/payment/cancel', [PaymentController::class, 'cancelPayment']);

Route::post('auth/send-otp',[AuthController::class,'generateOTP']);
Route::post('auth/verify-otp',[AuthController::class,'otpVerify']);
Route::post('auth/refresh', [AuthController::class, 'refresh']);

// Route::middleware('jwt.auth')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('user/save-profile', [AuthController::class, 'saveProfile']);
    Route::post('upload-image', [AuthController::class, 'uploadImage']);
    Route::post('user/profile', [AuthController::class, 'getUser']);
    
    Route::post('category/services', [ApiServiceController::class, 'getServicesByCategory']);
    Route::post('service/details', [ApiServiceController::class, 'serviceDetailsById']);
    Route::post('product/details', [ProductController::class, 'productDetailsById']);
    Route::post('services/list', [ApiServiceController::class, 'getAllServices']);
    Route::post('product/list', [ProductController::class, 'index']);
    Route::post('bookings/{bookingId}/reject', [ApiServiceProfessionalBookingController::class, 'rejectBooking']);

    /* Route::get('/chats', [ChatController::class, 'index']);
    Route::post('/chat/start', [ChatController::class, 'startOrGet']);
    Route::get('/chats/{chat}', [ChatController::class, 'show']);
    Route::post('/chats/{chat}/messages', [ChatController::class, 'send']);
    Route::post('/chats/{chat}/read', [ChatController::class, 'markAsRead']); */    
// });

// SP API
Route::prefix('sp')->group(function () {
    Route::post('/signup', [SpAuthController::class, 'signup']);

    // Route::middleware('jwt.auth')->group(function () {
        Route::post('items/save', [ApiServiceController::class, 'saveItem']);
        Route::delete('services/{id}', [ApiServiceController::class, 'deleteService']);
        Route::delete('product/{id}', [ProductController::class, 'deleteProduct']);

        Route::post('/bookings', [ApiServiceProfessionalBookingController::class, 'getSpBookings']);
        Route::post('bookings/{bookingId}/approve', [ApiServiceProfessionalBookingController::class, 'approveBooking']);

        Route::post('/plan/purchase', [ApiSubscriptionPlanController::class, 'purchasePlan']);
        Route::post('/plan/payment/verify', [PaymentController::class, 'verifyRazorpayPaymentForPlan']);

        Route::post('/job-post/{jobPost}/bid', [ApiJobPostController::class, 'placeBid']);

        /* Route::post('/product/store', [ProductController::class, 'store']);
        Route::get('/product/details/{id}', [ProductController::class, 'show']);
        Route::post('/product/update', [ProductController::class, 'update']);
        Route::get('/product/update/status/{id}', [ProductController::class, 'changeStatus']); */
        
    // });
    
});
// END SP API

Route::prefix('notifications')->group(function () {
    Route::middleware('jwt.auth')->group(function () {
        Route::get('/', [NotificationController::class, 'getNotifications']);
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount']);
        Route::post('/{notificationId}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);
    });
});

//Customers API
Route::prefix('customer')->group(function () {

    // Route::middleware('jwt.auth')->group(function () {
        Route::post('/home', [AuthController::class, 'homeData']);

        Route::post('/bookings', [BookingController::class, 'booking']);
        Route::post('/booking/list', [BookingController::class, 'getCustomerBookings']);
        Route::post('/bookings/{bookingId}/complete', [BookingController::class, 'completeService']);
        Route::get('/bookings/{bookingId}', [BookingController::class, 'show']);
        Route::get('/bookings/{bookingId}/summary', [BookingController::class, 'getBookingSummary']);
        Route::post('/bookings/initiate-payment', [BookingController::class, 'initiatePayment']);

        Route::post('/job-post/save', [ApiJobPostController::class, 'saveItem']);
        Route::delete('/job-post/{id}', [ApiJobPostController::class, 'deleteItem']);
        Route::post('/job-post/{jobPost}/bids', [ApiJobPostController::class, 'getJobBids']);
        Route::post('/job-post/bid/{bid}/accept', [ApiJobPostController::class, 'acceptBid']);
        Route::post('/job-post/bid/{bid}/reject', [ApiJobPostController::class, 'rejectBid']);

        Route::post('/add-address', [AddressController::class, 'addAddress']);
        Route::post('/address', [AddressController::class, 'allAddressById']);

        //Address
        /* Route::prefix('address')->group(function () {
            Route::get('/list', [AddressController::class, 'index']);
            Route::get('/delete/{id}', [AddressController::class, 'destroy']);
            Route::post('/store', [AddressController::class, 'store']);
            Route::post('/update', [AddressController::class, 'update']);
        }); */

        //Wishlist
        /* Route::get('product/wishlist/all', [ApiProductController::class, 'AllWishlist']);
        Route::post('product/wishlist/store', [ApiProductController::class, 'wishlistStore']);
        Route::get('product/wishlist/{id}', [ApiProductController::class, 'wishlistRemove']); */

        //cart
        Route::prefix('cart')->group(function () {
            Route::get('/', [CartController::class, 'index']);
            Route::post('/add', [CartController::class, 'addToCart']);
            Route::post('/update/{rowId}', [CartController::class, 'updateQuantity']);
            Route::get('/remove/{rowId}', [CartController::class, 'remove']);
            Route::get('/clear', [CartController::class, 'clear']);
        });

    // });
});

//End Customer API

//Vendor API
/* Route::prefix('vendor')->group(function () {
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
}); */
//End Vendor API

