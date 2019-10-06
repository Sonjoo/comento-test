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

Route::get('/', 'CouponController@redirectBaseUrl');

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/coupons', 'CouponController@getCouponList')->name('coupons');

Route::post('/coupons', 'CouponController@createCoupons');

Route::get('/use/coupon', 'CouponController@getCouponCheckPage')->name('check_request_page');

Route::put('/use/coupon', 'CouponController@useCoupon');

Route::get('/check/coupon', 'CouponController@checkCoupon');

Route::get('/coupon', 'CouponController@getCheckResultPage')->name('get_checked_result');

Route::get('/publish', 'CouponController@getCouponPublishPage')->name('publish');

Route::get('/coupon-dashboard', 'CouponController@getCouponDashBoard')->name('dashboard');

Route::post('/coupon-dashboard', 'CouponController@createCouponData');
