@extends('layouts.app')
@section('content')
  <div class="container">
    <form id="create_coupons" action="{{ url('/coupons') }}" method="post">
      {{ csrf_field()}}
      @if($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif
      <div class="form-group">
        <input type="text" name="prefix" placeholder="고정값" maxlength="3" required>
      </div>
      <div class="form-group">
        <input type="text" name="group_name" placeholder="그룹명" required>
      </div>
      <div class="form-group">
        <button class="btn btn-primary" onclick="return alert('이 작업은 1분~5분 이내에 반영됩니다')" type="submit" name="button">발행하기</button>
      </div>
    </form>
    <div class="float-right">
      <a class="btn btn-primary" type="button" href="{{url('/coupons')}}">목록으로가기</a>
    </div>
  </div>
@endsection
