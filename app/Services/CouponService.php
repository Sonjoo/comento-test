<?php
namespace App\Services;

use App\Jobs\CreateCoupons;

use App\Coupon;
use App\CouponGroup;

use App\Dtos\CouponDTO;

class CouponService {

  public $length;

  public static function loadWithLength($length) {
    $instance = new self();
    $instance->length = $length;
    return $instance;
  }

  public function createCoupons(CouponDTO $couponInfo) {
    $group_id = null;
    $group_name = $couponInfo->group_name;
    $coupon_code = $this->generateCouponCodes($couponInfo->prefix);

    if (CouponGroup::where('name', $group_name)->count() === 0) {
      CouponGroup::create([
        'name' => $group_name,
        'code_length' => $couponInfo->length,
      ]);
    }
    else {
      $group_id = CouponGroup::where('name', $group_name)->get()[0]->id;
    }
    CreateCoupons::dispatch($couponInfo, $group_id);
  }

  private function setCouponGroup() {

  }

  private function generateCouponCodes(string $prefix, $entity_array) {
    for ($i = 0; $i < 100000; $i++) {
       $current_unix = now()->unix();
       if ($previous_unix === $now) {
         
       }
       $previous_unix = $current_unix;
    }
  }
}
