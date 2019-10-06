<?php

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

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/coupons', 'CouponController@getCouponList');

Route::get('/use/coupon', 'CouponController@getCouponCheckPage');

Route::put('/use/coupon', 'CouponController@useCoupon');

Route::get('/coupon', 'CouponController@getCheckResultPage')->name('check_result');

Route::get('/check/coupon', 'CouponController@checkCoupon');
