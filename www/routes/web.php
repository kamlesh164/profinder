<?php

use App\Http\Controllers\admin\AdminInfoCategoryController;
use App\Http\Controllers\admin\AdminServiceProfessionalController;
use App\Http\Controllers\admin\auth\AuthController;
use App\Http\Controllers\admin\BlogController;
use App\Http\Controllers\admin\CustomerController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\CityController;
use App\Http\Controllers\admin\CoupanController;
use App\Http\Controllers\admin\DesignationController;
use App\Http\Controllers\admin\NotificationController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\PackageController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ReportController;
use App\Http\Controllers\admin\ServiceCategoryController;
use App\Http\Controllers\admin\ServiceController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\SliderController;
use App\Http\Controllers\admin\StateController;
use App\Http\Controllers\admin\SubscriptionController;
use App\Http\Controllers\admin\VendorController;
use App\Http\Controllers\admin\ApartmentController;
use App\Http\Controllers\admin\JobPostController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/cache', function () {
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return 'Config and cache cleared successfully!';
});
Route::get('/', [App\Http\Controllers\FrontHomeController::class, 'index'])->name('home');
Route::get('/about', [App\Http\Controllers\AboutUsController::class, 'index'])->name('about');
Route::get('/subscription', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscription');
Route::get('/services', [App\Http\Controllers\ServiceController::class, 'index'])->name('services');
Route::get('/service-professional', [App\Http\Controllers\ServiceProfessionalController::class, 'index'])->name('service-professional');
Route::get('/blogs', [App\Http\Controllers\BlogController::class, 'index'])->name('blogs');
Route::get('/faqs', [App\Http\Controllers\FAQsController::class, 'index'])->name('faqs');
Route::get('/contact-us', [App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::get('/privacy-policy', [App\Http\Controllers\PrivacyController::class, 'index']);
Route::get('/terms-and-conditions', [App\Http\Controllers\TermsController::class, 'index']);
Route::get('/refund-policy', [App\Http\Controllers\RefundController::class, 'index']);

Route::get('/test-payment/{session_id}', function($session_id) {
    return view('test-payment', ['session_id' => $session_id]);
});

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('admin/get-cities-by-state', [CityController::class, 'getCities']);

Route::match(['get', 'post'], 'admin', [AuthController::class, 'login'])->name('admin.login');

Route::middleware(['auth:admin', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('signout');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::resource('/customers', CustomerController::class);
    Route::put('/customer/status/{id}', [CustomerController::class, 'changeStatus']);
    Route::resource('/category', CategoryController::class);
    Route::put('/category/status/{id}', [CategoryController::class, 'changeStatus']);
    Route::resource('/subcategory', SubCategoryController::class);
    Route::resource('/packages', PackageController::class);

    Route::resource('designations', DesignationController::class);
    Route::put('designations/status/{id}', [DesignationController::class, 'UpdateStatus']);
    Route::resource('states', StateController::class);
    Route::resource('cities', CityController::class);
    Route::resource('sliders', SliderController::class);
    Route::put('slider-status/{id}', [SliderController::class, 'changeStatus'])->name('slider-status');

    Route::post('info-categories', [AdminInfoCategoryController::class, 'store'])->name('info-categories.store');
    Route::delete('info-categories/{id}', [AdminInfoCategoryController::class, 'destroy'])->name('info-categories.destroy');
    Route::get('info-categories/check-name', [AdminInfoCategoryController::class, 'checkName'])->name('info-categories.check-name');

    Route::get('subscriptions/purchase-history', [SubscriptionController::class, 'purchaseHistory'])
            ->name('subscriptions.purchase-history');
            
    Route::resource('subscriptions', SubscriptionController::class);
    Route::put('/admin/subscriptions/{subscription_plan}', [SubscriptionController::class, 'update'])->name('admin.subscriptions.update');

    Route::resource('jobpost', JobPostController::class);

    Route::resource('/service-professional', AdminServiceProfessionalController::class);
    Route::put('service-professional/status/{id}', [AdminServiceProfessionalController::class, 'changeStatus'])->name('service-professional.status');

    Route::resource('vendors', VendorController::class);
    Route::put('vendor/status/{id}', [VendorController::class, 'changeStatus'])->name('vendor.status');
    Route::get('vendor/kyc/pending', [VendorController::class, 'pendingKyc'])->name('vendor.kyc.pending');
    Route::get('vendor/kyc/details/{id}', [VendorController::class, 'kycDetails'])->name('vendor.kyc.details');
    Route::get('vendor/kyc/approval', [VendorController::class, 'kycApproval'])->name('vendor.kyc.approval');
        Route::post('vendor/import/excel', [VendorController::class, 'importExcel'])->name('vendor.upload.excel');


    Route::get('products', [ProductController::class, 'index'])->name('product.index');
    Route::get('products/details/{product}', [ProductController::class, 'details'])->name('product.details');
    Route::put('product/status/{id}', [ProductController::class, 'changeStatus'])->name('product.status');
    Route::get('products/approval', [ProductController::class, 'approval'])->name('product.approval');

    //Orders Management
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('order.index');
        Route::get('/show/{id}', [OrderController::class, 'show'])->name('order.show');
        Route::get('/pending', [OrderController::class, 'pendingOrders'])->name('order.pending');
        Route::get('/complete', [OrderController::class, 'completeOrders'])->name('order.complete');
        Route::post('/update/status', [OrderController::class, 'completeOrders'])->name('order.update.status');
    });

    //Blogs
    Route::resource('blogs', BlogController::class);
    Route::resource('servicecategory', ServiceCategoryController::class);
    Route::put('servicecategory/status/{id}', [ServiceCategoryController::class, 'changeStatus']);
    Route::resource('service', ServiceController::class);

    //Apartments
    Route::prefix('apartments')->group(function () {
        Route::get('/', [ApartmentController::class, 'apartments'])->name('apartment.index');
        Route::post('/store', [ApartmentController::class, 'apartmentStore'])->name('apartment.store');
        Route::post('/update', [ApartmentController::class, 'apartmentUpdate'])->name('apartment.update');
        Route::delete('/delete', [ApartmentController::class, 'apartmentDelete'])->name('apartment.delete');
    });
    Route::resource('notification', NotificationController::class);
    Route::put('notification/status/{id}', [NotificationController::class, 'changeStatus']);

    //Coupan
    Route::resource('coupan', CoupanController::class);
    Route::put('coupan/status/{id}', [CoupanController::class, 'changeStatus'])->name('coupan-status');

    //Setting
    Route::get('/settings', [SettingController::class, 'index'])->name('setting');
    Route::post('/update/logo-favicon', [SettingController::class, 'updateLogoFavicon'])->name('update.logo-favicon');

    //Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('vendor.report.index');
    Route::get('/reports/get', [ReportController::class, 'getReport'])->name('vendor.report.get');

});