<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponGroup extends Model
{
    public function coupons() {
      $this->hasMany('App\Coupon');
    }
}
