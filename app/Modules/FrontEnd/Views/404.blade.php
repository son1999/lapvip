@extends('FrontEnd::layouts.home')

@section ('content')
<div class="container my-5 py-5">
  <div id="page-wrap" class="page_404">
    <div class="container text-center">
        <img src="{{asset('images/404.png')}}" alt="Trang 404">
          <h4>Không tìm thấy trang</h4>
          <a href="/" class="btn-success mt-3 btn">Quay lại trang chủ</a>
    </div>
  </div>
</div>
@stop
