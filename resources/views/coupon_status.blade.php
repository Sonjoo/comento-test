@extends('layouts.app')

@section('content')
  <div class="container">
    <div>
      <h1>{{$name}}님!</h1>
      @if($is_usable == 1)
      <h4>입력하신 {{$coupon_code}} 는 사용 가능합니다</h4>
      <h3>사용하시려면 아래 버튼을 눌러주세요</h3>
      <form action="{{url('/use/coupon')}}" method="post">
        @method('put')
        {{csrf_field()}}
        <input type="text" name="code" hidden value="{{$coupon_code}}" readonly>
        <div class="form-group">
          <button class="btn btn-primary" type="submit">사용하기</button>
        </div>
      </form>
      @else
      <h4>입력하신 {{$coupon_code}} 는 유효하지 않습니다</h4>
      @endif
    </div>
    <div class="content">
      <a class="btn btn-primary" href="{{url('/use/coupon')}}">되돌아가기</a>
    </div>
  </div>
@endsection
