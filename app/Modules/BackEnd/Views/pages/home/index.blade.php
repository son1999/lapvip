@extends('BackEnd::layouts.default')

@section('content')
<div class="jumbotron">
    <h1 class="display-3">Xin chào, {{ \Auth::user()->fullname }}</h1>
    <p class="lead">Đây là trang quản trị của dự án {{ env('APP_NAME') }}</p>
    <hr class="my-4">
    <p>Trong quá trình thao tác bạn gặp khó khăn hoặc cần cung cấp thêm quyền sử dụng vui lòng liên hệ với Admin</p>
    <p class="lead">
        <a class="btn btn-primary btn-lg" href="mailto:" role="button"><i class="fe-mail"></i>&nbsp; Gửi email liên hệ</a>
    </p>
</div>
@stop