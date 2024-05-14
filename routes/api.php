<?php

use App\Http\Controllers\LoginApiController;
use App\Http\Controllers\MemberApiController;
use App\Http\Controllers\NotificationApiController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StoresController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PaymentController;

use App\Http\Controllers\RegistrationController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/category', [CategoryController::class,'api_parent']);
Route::get('/category/{id}', [CategoryController::class,'api_parent']);
 */

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // Route::get('/category', [CategoryController::class, 'api_parent']);
    // Route::get('/category/{id}', [CategoryController::class, 'api_parent']);
    // Route::get('/store/{category_id}', [StoresController::class, 'api_category_wise']);

    // Route::get('/coupons', [CouponController::class, 'api_store_wise']);
    // Route::get('/coupons/{store_id}', [CouponController::class, 'api_store_wise']);
});
Route::middleware(['jwt'])->group(function () {
    Route::post('/changePassword', [RegistrationController::class, 'changePassword']);
    Route::post('/upateProfile', [RegistrationController::class, 'upateProfile']);


    Route::put('/update-User-Info', [LoginApiController::class, 'updateUserInfo']);
    Route::get('/user-Info', [LoginApiController::class, 'userInfo']);

    Route::get('/vip_member_benefits', [MemberApiController::class, 'vip_member_benefits']);
    Route::get('/membership_details', [MemberApiController::class, 'membership_details']);


    /////////////////////// Notification Api Starts ///////////////////////////////////
    Route::post('/notifications', [NotificationApiController::class, 'notifications']);
    Route::get('/get-notification-details/{notificationId}', [NotificationApiController::class, 'notificationDetails']);
    Route::post('/read-notifications', [NotificationApiController::class, 'readNotifications']);
    Route::post('/updateNotificationToken', [NotificationApiController::class, 'UpdateNotificationToken']);

    /////////////////////// Notification Api Ends /////////////////////////////////////

});

Route::post('/registration', [RegistrationController::class, 'registration']);
Route::post('/login', [RegistrationController::class, 'login']);
Route::post('/forgotPassword', [RegistrationController::class, 'forgotPassword']);
Route::post('/resetPassword/{id}', [RegistrationController::class, 'resetPassword'])->name('user-reset-password');

Route::get('/category', [CategoryController::class, 'api_parent']);
Route::get('/category/{id}', [CategoryController::class, 'api_parent']);
Route::get('/store/{category_id}', [StoresController::class, 'api_category_wise']);

///////////////////// Merchandise API Starts ////////////////////////////
Route::get('/products', [StoresController::class, 'api_crone_product_lists']);
Route::get('/products/{product_id}', [StoresController::class, 'api_product_info']);
Route::get('/product_lists', [StoresController::class, 'api_product_lists']);
Route::post('/create_orders', [OrderController::class, 'api_create_orders']);
Route::post('/cancel_order/{order_id}', [OrderController::class, 'api_cancel_order']);
Route::post('/get_order_details/{order_id}', [OrderController::class, 'get_order_details']);
///////////////////// Merchandise API Ends ////////////////////////////


Route::get('/coupons', [CouponController::class, 'api_store_wise']);
Route::get('/coupons/{store_id}', [CouponController::class, 'api_store_wise']);


Route::get('/events', [EventController::class, 'allevent']);


///////////////////// User Login Form Api Starts ///////////////////////////////////
Route::post('/loginWithEmail', [LoginApiController::class, 'loginWithEmail']);
Route::post('/registerCustomer', [LoginApiController::class, 'registerCustomer']);
Route::post('/forgotPasswordEmailOrPhone', [LoginApiController::class, 'forgotPasswordEmailOrPhone']);
Route::post('/verifyOtp', [LoginApiController::class, 'verifyOtp']);
Route::post('/resetPassword', [LoginApiController::class, 'resetPassword'])->name('resetPassword');
Route::put('/updatePassword', [LoginApiController::class, 'updatePassword'])->name('updatePassword');

//////////////////// User Login Form Api Ends  ////////////////////////////////////




///////////////////// Route For Stripe Payment Starts ///////////////////
Route::post('/create_stripe_customer', [PaymentController::class, 'api_create_stripe_customer']);
Route::post('/add_payment_method', [PaymentController::class, 'api_add_payment_method']);
Route::post('/remove_payment_method', [PaymentController::class, 'api_remove_payment_method']);
////////////////////  Route For Stripe Payment Ends ////////////////////
