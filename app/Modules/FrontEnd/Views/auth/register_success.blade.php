@extends('FrontEnd::layouts.home')

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop

@section('content')
<div id="content" class="section content-wrap content-info">
    <div class="container clearfix">
        <div class="travel-info-wrap">
            <div class="page-title ta-c fw-rbm fs-30 fc-black">Đăng kí tài khoản thành công</div>
            <div class="fs-14 fc-black" align="center">
                <p>Chào mừng bạn đến với <span class="fw-ob">{{ env('APP_NAME') }}</span> - chuyên trang mua hàng trực tuyến.</p>
                <p>Email kích hoạt tài khoản đã được gửi tới địa chỉ email <span class="fw-ob">{{ $email }}</span> do bạn đăng kí.</p>
                <p>Vui lòng kiểm tra tất cả các hộp thư <span>Inbox/Spam/Bulk</span> và làm theo hướng dẫn để kích hoạt và đăng nhập.</p>
            </div>
        </div>
    </div>
</div>
@stop