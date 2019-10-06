<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\CouponService;

use App\Dtos\CouponDTO;

use App\Coupon;
use App\CouponGroup;
use App\User;
use App\CouponData;

class CouponController extends Controller
{
    protected $couponService;

    /**
    *
    * @param  CouponService  $couponService
    * @return void
    */
    public function __construct(CouponService $couponService) {
      //middleware auth 를 생성자에 추가하는 경우
      //인증되지 않은 경우에는 CouponController로 들어오는 항목들 '/'로 리다이렉트
      $this->middleware('auth');
      $this->couponService = $couponService;
    }

    public function redirectBaseUrl() {
      //'/'로 들어오는 모든 url 을 인증 여부를 확인하고
      //각 권한에 알맞는 곳으로 redirect
      if(Auth::check()) {
        $user = Auth::user();
        if ($user->code === 'admin') {
          return redirect()->route('coupons');
        }
        return redirect()->route('check_request_page');
      }
      return view('auth.login');
    }


    public function getCouponCheckPage() {
      $user = Auth::user();
      return view('check_coupon', ['name' => $user->name]);
    }

    public function getCheckResultPage(Request $request) {
      return view('coupon_status', [
        'name' => Auth::user()->name,
        'is_usable' => $request->input('is_usable'),
        'coupon_code' => $request->input('coupon_code'),
      ]);
    }

    public function checkCoupon(Request $request) {
      //라라벨 validate의 기본적인 사용
      //최소 최대 각4 필수값 지정
      $request->validate([
        'first_code' => 'required|min:4|max:4',
        'second_code' => 'required|min:4|max:4',
        'third_code' => 'required|min:4|max:4',
        'fourth_code' => 'required|min:4|max:4',
      ]);
      $check_result = $this->couponService->checkCoupon(
        Auth::id(),
        [
          $request->input('first_code'),
          $request->input('second_code'),
          $request->input('third_code'),
          $request->input('fourth_code'),
        ]
      );
      return redirect()->route('get_checked_result', ['is_usable' => $check_result['is_usable'], 'coupon_code' => $check_result['code']]);
    }

    public function useCoupon(Request $request) {
      $this->couponService->useCoupon(Auth::id(), $request->input('code'));
      return redirect()->action('CouponController@getCouponCheckPage');
    }

    public function createCoupons(Request $request) {
      $request->validate([
        'prefix' => 'required|min:3|max:3',
        'group_name' => 'required',
      ]);
      if (Auth::user()->code !== 'admin') {
        return response('404');
      }
      $couponInfo = new CouponDTO($request->input('prefix'), $request->input('group_name'), $request->input('length', 16));
      $this->couponService->createCoupons($couponInfo);

      return redirect()->route('coupons');
    }

    public function getCouponList(Request $request) {
      $coupons = null;
      if (Auth::user()->code !== 'admin') {
        return redirect()->route('check_request_page');
      }
      //특정 그룹 명이 들어오는경우 그룹명과 함께 리턴,
      //model->paginate 는 데이터 과다로 지나치게 오래 걸리기 때문에
      //simple paginate로 앞뒤만 지정
      $group_name = $request->input('group_name');
      if ($group_name != null) {
        $coupons = Coupon::with([
          'couponGroup' => function($query) {$query->select('id','name');},
          'user' => function ($query) {$query->select('id', 'email');},
        ])
        ->whereHas('couponGroup', function ($query) use ($group_name) {$query->where('name', 'like', '%'.$group_name.'%');})
        ->simplePaginate(100);
      } else {
        //특정 그룹명이 없으면 단순 조회  정렬
        $coupons = Coupon::with([
            'couponGroup' => function($query){$query->select('id','name');},
            'user' => function ($query) {$query->select('id', 'email');},
        ])
        ->simplePaginate(100);
      }

      return view('coupon_list', ['coupons' => $coupons]);
    }

    public function getCouponPublishPage() {
      $user = Auth::user();
      if ($user->code !== 'admin') {
        return response('404');
      }
      return view('generate_coupon', ['name' => $user->name]);
    }

    public function getCouponDashBoard() {
      $user = Auth::user();
      if ($user->code !== 'admin') {
        return response('404');
      }
      $data = CouponData::with([
          'couponGroup' => function($query){$query->select('id','name');},
      ])
      ->paginate(100);
      return view('coupon_dashboard', ['data' => $data]);
    }

    public function createCouponData() {
      $user = Auth::user();
      if ($user->code !== 'admin') {
        return response('404');
      }
      $this->couponService->createCouponData();
      return redirect()->route('dashboard');
    }
}
