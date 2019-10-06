@extends('layouts.app')

@section('content')
<div class="float-right">
  <a role="button" class="btn btn-primary btn-lg" href="{{url('/coupons')}}">목록으로가기</a>
  <a role="button" class="btn btn-primary btn-lg" href="{{url('/publish')}}">발행하러 가기</a>
</div>
<div class="container">
  <form action="{{url('/coupon-dashboard')}}" method="post">
    {{csrf_field()}}
    <div class="form-row">
      <h4>지금바로 보고싶다면?!</h4>
      <button class="btn btn-danger" onclick="return alert('이 작업은 몇분 이상 소요될 수 있습니다.')" type="submit" name="button">클릭</button>
    </div>
  </form>
  <table class="table">
    <thead>
      <tr>
        <th>그룹아이디</th>
        <th>그룹이름</th>
        <th>사용량</th>
        <th>사용 퍼센트</th>
        <th>총 수량</th>
        <th>생성일시</th>
      </tr>
    </thead>
    <tbody>
      @if($data->count() === 0)
      <td>통계 자료가 없습니다</td>
      @else
      @foreach($data as $data_by_group)
        <tr>
          <td>{{$data_by_group->id}}</td>
          <td>{{$data_by_group->couponGroup->name}}</td>
          <td>{{$data_by_group->used_num}}</td>
          <td>약 {{$data_by_group->used_percentage}}%</td>
          <td>{{$data_by_group->total_num}}</td>
          <td>{{$data_by_group->created_at}}</td>
        </tr>
      @endforeach
      @endif
    </tbody>
  </table>
  @if($data->count() != 0)
  {{$data->links()}}
  @endif

</div>
@endsection
