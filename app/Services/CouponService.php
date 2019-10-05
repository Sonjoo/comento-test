<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Jobs\CreateCoupons;

use App\Coupon;
use App\CouponGroup;

use App\Dtos\CouponDTO;

class CouponService {

  private $length;

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

  public function generateCouponCodes(string $prefix, $entity_array) {
    $length = $this->length - strlen($prefix);
    $coupons = [];
    for ($i = 0; $i < 100000; $i++) {
      $created_code = $prefix.$this->createCouponCode($length);
      $entity_array['id'] = $created_code;
      $coupon = Coupon::find($entity_array['id']);
      while ($coupon !== null) {
        $entity_array['id'] = $prefix.$this->createCouponCode($length);
        $coupon = Coupon::find($entity_array['id']);
      }
      array_push($coupons, $entity_array);
      if ($i % 10000 === 0 && $i !== 0) {
        DB::table('coupons')->insert($coupons);
        $coupons = [];
      } else if ($i === 99999) {
        DB::table('coupons')->insert($coupons);
      }
    }
  }

  private function createCouponCode($length) {
    $code = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    $max = strlen($codeAlphabet);
    for ($i = 0; $i < $length; $i++) {
        $code = $code.$codeAlphabet[random_int(0, $max-1)];
    }
    return $code;
  }
}
