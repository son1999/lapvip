@extends('FrontEnd::layouts.default')

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop

@section('content')
    <h2>Đăng nhập thất bại</h2>
    <p class="text-danger" align="center">Đã có lỗi xảy ra khi đăng nhập bằng tài khoản mạng xã hội!!!</p>
    <p class="text-danger" align="center">Hãy thử đăng nhập lại</p>
    <p><a href="javascript:void(0)" onclick="$('#detailErr').slideToggle()">Xem chi tiết</a></p>
    <p id="detailErr" style="display: none">{!! $msg !!}</p>
@stop