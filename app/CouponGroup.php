<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponGroup extends Model
{
    protected $guarded = [];

    public function coupons() {
      return $this->hasMany('App\Coupon');
    }

    public function couponData() {
      return $this->hasMany('App\CouponGroup');
    }
}
