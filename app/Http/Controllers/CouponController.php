<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Coupon;

class CouponController extends Controller
{
    public function getCouponList(Request $request) {
      $coupons = null;
      if ($request->input('group_id') !== null) {
        $coupons = Coupon::where('group_id', $request->input('group_id'))->paginate(100);
      } else {
        $coupons = Coupon::paginate(100);
      }
    }
}
