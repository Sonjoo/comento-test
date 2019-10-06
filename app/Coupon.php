<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $keyType = 'string';

    protected $guarded = [];

    public $incrementing = false;

    public function couponGroup() {
      return $this->belongsTo('App\CouponGroup');
    }

    public function user() {
      return $this->belongsTo('App\User');
    }
}
