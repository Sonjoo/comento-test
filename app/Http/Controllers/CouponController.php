<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\CouponService;

use App\Dtos\CouponDTO;

use App\Coupon;

class CouponController extends Controller
{
    private $couponService;

    public function __construct(CouponService $couponService) {
      $this->couponService = $couponService;
    }

    public function createCoupons(Request $request) {
      $couponInfo = new CouponDTO($request->input('prefix'), $request->input('group_name'), $request->input('length', 16));
      $is_success = $this->couponService($couponInfo);
      if ($is_success) {
        return ['success' => true];
      } else {
        return ['success' => false];
      }
    }

    public function getCouponList(Request $request) {
      $coupons = null;
      if ($request->input('group_id') !== null) {
        $coupons = Coupon::where('group_id', $request->input('group_id'))->paginate(100);
      } else {
        $coupons = Coupon::paginate(100);
      }
      return $coupons;
    }
}
