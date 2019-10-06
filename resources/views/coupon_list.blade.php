@extends('layouts.app')

@section('content')
<div class="float-right">
  <a role="button" class="btn btn-primary btn-lg" href="{{url('/coupon-dashboard')}}">통계보러가기</a>
  <a role="button" class="btn btn-primary btn-lg" href="{{url('/publish')}}">발행하러 가기</a>
</div>
<div class="container">
  <form action="{{url('/coupons')}}" method="get">
    <div class="form-group">
      <input type="text" name="group_name" placeholder="그룹명">
      <button class="btn btn-primary" type="submit" name="button">검색!</button>
    </div>
  </form>
  <table class="table">
    <thead>
      <tr>
        <th>쿠폰 코드</th>
        <th>그룹</th>
        <th>사용유저DB id</th>
        <th>사용유저 email</th>
        <th>사용여부</th>
        <th>사용 일시</th>
        <th>생성일시</th>
      </tr>
    </thead>
    <tbody>
      @if($coupons->count() === 0)
      <td>쿠폰이 없습니다</td>
      @else
      @foreach($coupons as $coupon)
        <tr @if($coupon->using == 0) class="table-success"@endif>
          <td>{{$coupon->id}}</td>
          <td>{{$coupon->couponGroup->name}}</td>
          <td>{{$coupon->user->id}}</td>
          <td>{{$coupon->user->email}}</td>
          @if ($coupon->using == 0)
          <td>사용 완료</td>
          @else
          <td>사용전</td>
          @endif
          <td>{{$coupon->used_at}}</td>
          <td>{{$coupon->created_at}}</td>
        </tr>
      @endforeach
      @endif
    </tbody>
  </table>
  @if($coupons->count() != 0)
    {{$coupons->appends(request()->input())->links()}}
  @endif
</div>
@endsection
