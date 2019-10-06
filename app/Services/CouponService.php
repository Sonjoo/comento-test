<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Jobs\CreateCoupons;

use App\Coupon;
use App\CouponGroup;
use App\User;
use App\CouponData;

use App\Dtos\CouponDTO;

class CouponService {

  private $length;

  //queue job 에서 $lenth 와 함께 생성 하기 위한 다중 생성자
  public static function loadWithLength($length) {
    $instance = new self();
    $instance->length = $length;
    return $instance;
  }

  //본래 배치를 위한 작업이기에 static으로 빼놓았음.
  public static function createCouponData() {
    //전체 그룹의 엔티티를 가져옴
    $groups = CouponGroup::get();
    //각 그룹별로 가벼운 통계를 낸다.
    foreach ($groups as $group) {
      $total_num = $group->coupons()->count();
      $used_num = $group->coupons()->where('using', 0)->count();
      //0으로 나눌수 없기에 별도의 예외처리를 진행함.
      //일부 테스트 케이스를 제외하거나 실 프로덕션에 올라서 기능이 붙지 않는이상 큰 의미는 없음
      if ($total_num !== 0) {
        $percentage = ($used_num*100)/$total_num;
      } else {
        $percentage = 0;
      }

      //기존 데이터를 수정하고 가져오기보다, 양이 많아질 가능성을 염두에 두고 일괄 삭제
      //History 가 필요한 경우 별도로 history 테이블을 작성하는 것이 나아 보임.
      //또는 state등의 칼럼추가를 통해 현재 보여질 값을 결정할 수 있으나 시간의 한계로 멈춤.
      $data = CouponData::where('group_id')->first();
      if ($data !== null) {
        CouponData::where('group_id')->first()->delete();
      }

      CouponData::create([
        'used_percentage' => $percentage,
        'used_num' => $used_num,
        'total_num' => $total_num,
        'group_id' => $group->id,
      ]);
    }
  }

  public function useCoupon($user_id, $coupon_code) {
    if(!$this->checkCodeIsValid($user_id, $coupon_code)) {
      return false;
    }
    Coupon::find($coupon_code)
      ->update([
        'using' => 0,
        'used_at' => date('Y-m-d H:i:s'),
      ]);
    return true;
  }

  public function checkCoupon($user_id, $coupon_codes) {
    $code = '';
    for ($i=0; $i < count($coupon_codes); $i++) {
      $code = $code.$coupon_codes[$i];
    }
    return [
      'code' => $code,
      'is_usable' => $this->checkCodeIsValid($user_id, $code),
    ];
  }

  public function createCoupons(CouponDTO $couponInfo) {
    $group_id = null;
    $group_name = $couponInfo->group_name;
    //CouponGroup entity 가 없는 경우 생성, 있으면 해당 entity로 사용
    if (CouponGroup::where('name', $group_name)->count() === 0) {
      $group_id = CouponGroup::create([
        'name' => $group_name,
        'code_length' => $couponInfo->length,
      ])->id;
    }
    else {
      $group_id = CouponGroup::where('name', $group_name)->first()->id;
    }
    //성능을 위해서 queue 로 전달.
    //@param Coupon의 정보와 Coupon Group id
    CreateCoupons::dispatch($couponInfo, $group_id);
  }

  /**
  * @param string $prefix
  * prefix 값 $prefix
  *
  * @param mixed $entity_array
  * id, group_id, created_at, updated_at 등을 포함한 값.
  *
  * 성능 환경, ram: ddr4 4G
  * cpu i7 5세대
  * gpu 내장
  * 노트북
  * 기존에 존재하지 않는 prefix 인 경우 약 70후반~90초 이내
  * prefix 중복시에 80대->120대->160대 순으로 증가하는 현상 확인
  * 시간 한계로 구체적인 확인 실패.
  * 추측컨대 db에서 orm find 를 통해 검색하는 방식에서 문제가 있다고 보여짐.
  * @return void
  */
  public function generateCouponCodes(string $prefix, $entity_array) {
    $length = $this->length - strlen($prefix);
    $coupons = [];
    $entity_array['prefix'] = $prefix;
    $users = User::where('code', '!=', 'admin')->get();
    $users_num = $users->count();
    //10만개의 데이터 생성
    for ($i = 0; $i < 100000; $i++) {
      $created_code = $prefix.$this->createCouponCode($length);
      $coupon = Coupon::find($created_code);
      //중복되는 코드가 발생하는 경우 중복이 발생하지 않는 코드가 나올때 까지
      //검사
      while ($coupon !== null) {
        $created_code = $prefix.$this->createCouponCode($length);
        $coupon = Coupon::find($created_code);
      }
      $entity_array['user_id'] = $users[random_int(0, $users_num - 1)]->id;
      $entity_array['id'] = $created_code;
      array_push($coupons, $entity_array);
      //10만개는 수용가능치를 넘어서기에 1만개 단위로 잘라서 저장
      if ($i % 10000 === 0 && $i !== 0) {
      //성능을 위해서 DB파사드를 이용한 인서트 실행
        DB::table('coupons')->insert($coupons);
        $coupons = [];
      } else if ($i === 99999) {
        DB::table('coupons')->insert($coupons);
      }
    }
  }

  //2회 이상 중복되는 if문 덩치이기에 분할
  private function checkCodeIsValid($id, $code) {
    $coupon = Coupon::find($code);
    if ($coupon === null) {
      return false;
    }
    else if ($coupon->user_id !== $id) {
      return false;
    }
    else if ($coupon->using === 0) {
      return false;
    }
    return true;
  }

  /**
  *쿠폰의 코드를 만들어내는 소스
  *@param int $length
  * 만들어질 코드의 길이
  *
  * @return string $code
  * 생성된 코드
  */
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
