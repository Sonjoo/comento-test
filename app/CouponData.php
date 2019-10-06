<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponData extends Model
{
    protected $guarded = [];

    public function couponGroup() {
      return $this->belongsTo('App\CouponGroup', 'group_id');
    }
}
