<?php

use App\Http\Controllers\CouponController;
use App\Http\Controllers\CouponsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VipMembershipController;
use App\Http\Controllers\StoresController;
use App\Http\Controllers\ClientOrderController;

use App\Http\Controllers\ProductAllController;




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

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::view('about', 'about')->name('about');
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('users', [UserController::class, 'index'])->name('users.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Store Route Start

    Route::get('stores/list', [StoresController::class, 'index'])->name('store-list');
    Route::get('stores/getAll', [StoresController::class, 'getAll'])->name('store-get-all');
    Route::post('stores/store', [StoresController::class, 'store'])->name('store-store');
    Route::get('stores/add', [StoresController::class, 'create'])->name('store-add');
    Route::get('stores/edit/{store}', [StoresController::class, 'edit'])->name('store-edit');
    Route::put('stores/update/{store}', [StoresController::class, 'update'])->name('store-update');
    Route::get('stores/destroy/{store}', [StoresController::class, 'destroy'])->name('store-delete');
    // Store Route End

    /////////////// Route For Coupon//////////
    Route::view('coupon-list', 'coupon')->name('coupon');

    Route::get('coupons', [CouponController::class, 'index'])->name('coupon.index');
    Route::get('coupons/create', [CouponController::class, 'create'])->name('coupon.create');
    Route::post('coupons', [CouponController::class, 'store'])->name('coupon.store');
    Route::get('coupons/{coupon}/edit', [CouponController::class, 'edit'])->name('coupon.edit');
    Route::put('coupons/{coupon}', [CouponController::class, 'update'])->name('coupon.update');
    Route::get('coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupon.destroy');




    // Route For VIP Membership benifits

    Route::view('vip_membership-list', 'vipMembership')->name('vip_membership');
    Route::get('vip_membership', [VipMembershipController::class, 'index'])->name('vip_membership.index');
    Route::get('vip_membership/create', [VipMembershipController::class, 'create'])->name('vip_membership.create');
    Route::post('vip_membership', [VipMembershipController::class, 'store'])->name('vip_membership.store');
    Route::get('vip_membership/edit/{vipMembership}', [VipMembershipController::class, 'edit'])->name('vip_membership.edit');
    Route::put('vip_membership/{vipMembership}', [VipMembershipController::class, 'update'])->name('vip_membership.update');
    Route::get('vip_membership/{vipMembership}', [VipMembershipController::class, 'destroy'])->name('vip_membership.destroy');



    // Route For VIP Membership benifits

    /////////////// Route For Event//////////
    Route::view('event-list', 'event')->name('event');

    Route::get('events', [EventController::class, 'index'])->name('event.index');
    Route::get('events/create', [EventController::class, 'create'])->name('event.create');
    Route::post('events', [EventController::class, 'store'])->name('event.store');
    Route::get('events/{event}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('events/{event}', [EventController::class, 'update'])->name('event.update');
    Route::get('events/{event}', [EventController::class, 'destroy'])->name('event.destroy');


    Route::get('products', [ProductAllController::class, 'index'])->name('product.index');
    Route::get('products/{product}/edit', [ProductAllController::class, 'edit'])->name('product.edit');
    Route::put('products/{product}', [ProductAllController::class, 'update'])->name('product.update');
    Route::get('products/{product}/view', [ProductAllController::class, 'product_view'])->name('product.view');

    Route::get('orders', [ClientOrderController::class, 'index'])->name('order.index');
    Route::get('orders/{orders}', [ClientOrderController::class, 'view_orders'])->name('order.views');

    Route::get('orders/delete/{order_id}', [ClientOrderController::class, 'destroy'])->name('order.destroy');
    Route::get('restaurants_package/orderemail', [ClientOrderController::class, 'orderEmail'])->name('app-order-email');

    /////////////Route For Users Starts//////////////////////////
    Route::get('users', [UserController::class, 'index'])->name('user.index');
    /////////////////Route For User Ends////////////////////////


    ///////////////////Route For Push Notification Starts/////////////////////////////
    Route::resource('pushNotification', PushNotificationController::class);

    ///////////////////Route For Push Notification Ends/////////////////////////////

    /**
     * category routes
     */
    Route::get('category', [CategoryController::class, 'index'])->name('category');
    /* Route::get('category/list', [CategoryController::class, 'index'])->name('category-list'); */
    //Route::get('category/getAll', [CategoryController::class, 'getAll'])->name('category-get-all');
    Route::get('category/add', [CategoryController::class, 'createCategory'])->name('category-add');
    Route::post('category/store', [CategoryController::class, 'store'])->name('category-store');
    Route::get('category/edit/{category}', [CategoryController::class, 'edit'])->name('category-edit');
    Route::put('category/update/{category}', [CategoryController::class, 'update'])->name('category-update');
    Route::delete('category/destroy/{category}', [CategoryController::class, 'destroy'])->name('category-delete');
});


require __DIR__ . '/auth.php';


