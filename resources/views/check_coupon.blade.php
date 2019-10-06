@extends('layouts.app')

@section('content')
  <div class="container">
    <form action={{url('/check/coupon')}} method="GET">
      <div class="form-group">
        <div>
          <h1>안녕하세요! {{$name}}님!</h1>
          <h4>쿠폰을 사용하시려면 아래에 입력해주세요</h4>
        </div>
        @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
      </div>
      <div class="form-row">
        <div class="form-group col-md-2">
          <input type="text" name="first_code" placeholder="####" maxlength="4" required>
          -
        </div>
        <div class="form-group col-md-2">
          <input type="text" name="second_code" placeholder="####" maxlength="4" required>
          -
        </div>
        <div class="form-group col-md-2">
          <input type="text" name="third_code" placeholder="####" maxlength="4" required>
          -
        </div>
        <div class="form-group col-md-2">
          <input type="text" name="fourth_code" placeholder="####" maxlength="4" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">확인하기</button>
    </form>
  </div>
@endsection
