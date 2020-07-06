@extends('FrontEnd::layouts.home')

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop

@section('content')
<div id="content" class="section content-wrap content-info">
    <div class="container clearfix">
        <div class="travel-info-wrap">
            <div class="page-title ta-c fw-rbm fs-30 fc-black">Kích hoạt tài khoản</div>
            <div class="fs-14 fc-black" align="center">
                @if ($err != '')
                    <p class="fc-red">Đã có lỗi xảy ra khi kích hoạt tài khoản!!!</p>
                    <p class="fw-ob fc-red">{{ $err }}</p>
                    @if($show_resend)
                    <div class="popup-form-btn">
                        <button class="btn btn-outline-primary" onclick="shop.sendActiveMail({{$customer->id}})">Gửi lại email kích hoạt</button>
                    </div>
                    @endif
                @else
                    <p class="fw-ob">Chúc mừng bạn đã kích hoạt tài khoản thành công.</p>
                    <p>Bạn đã có thể sử dụng email <span class="fw-ob">{{ $customer->email }}</span> để <a href="#" data-toggle="modal" data-target="#login" class="fw-ob read-more">Đăng nhập</a> vào hệ thống ngay bây giờ.</p>
                    <p>Nếu có bất kì thắc mắc nào, xin vui lòng liên hệ theo hotline <span class="fw-ob">(024)7300.2233</span></p>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('js_bot')
<script>
    shop.sendActiveMail = function(id){
        shop.ajax_popup('email-active', 'post', { id: id },
            function (json) {
                if (json.error == 0) {
                    alert('Email kích hoạt đã được gửi đi \n Vui lòng check inbox/spam/bulk và làm theo hướng dẫn');
                } else {
                    alert(json.msg);
                }
            });
    };
</script>
@endsection

