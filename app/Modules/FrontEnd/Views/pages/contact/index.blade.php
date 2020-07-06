@extends('FrontEnd::layouts.home')

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop

@section('content')
<main>
    <div class="breadcrumb-block">
        {!! \Lib::renderBreadcrumb(false, true,'FrontEnd::layouts.breadcrumb') !!}
    </div>
    <div class="container blog-page">
        <h2>Liên Hệ</h2>
        @if(@Session::has('success'))
            <div class="alert alert-success">
                <p>Xin được cám ơn ý kiến đóng góp của bạn! Yêu cầu của bạn sẽ được gửi tới Ban Quản Trị trong thời gian sớm nhất</p>
            </div>
        @endif
        <div class="row">
            <div class="col-md-6">
                {!! $def['address'] !!}
            </div>
            <div class="col-md-6 mb-5">
                <div class="contact-fill-form contact-form">
                    <div class="title"><span>Điền đủ thông tin</span></div>
                    <form action="{{route('contact.post')}}" method="post">
                        @csrf
                        <div class="fill name">
                            <input type="text" class="@error('name') is-invalid @enderror" name="name" placeholder="Họ và tên" value="{{old('name')}}">
                            <span class="icon"><i class="fa fa-user"></i></span>
                            @error('name')
                            <i style="color:  red">{{$message}}</i>
                            @enderror
                        </div>
                        <div class="fill email">
                            <input type="text" class="@error('email') is-invalid @enderror" name="email" placeholder="Emai của quý khách" value="{{old('email')}}">
                            <span class="icon"><i class="fa fa-envelope"></i></span>
                            @error('email')
                            <i style="color:  red">{{$message}}</i>
                            @enderror
                        </div>
                        <div class="fill phone">
                            <input type="text" class="@error('phone') is-invalid @enderror" name="phone" placeholder="Số điện thoại" value="{{old('phone')}}">
                            <span class="icon"><i class="fa fa-phone"></i></span>
                            @error('phone')
                            <i style="color:  red">{{$message}}</i>
                            @enderror
                        </div>
                        <div class="fill cart">
                            <input type="text" class="@error('code') is-invalid @enderror" name="code" placeholder="Mã đơn hàng" value="{{old('code')}}">
                            <span class="icon"><i class="fa fa-shopping-bag"></i></span>
                            @error('code')
                            <i style="color:  red">{{$message}}</i>
                            @enderror
                        </div>
                        <div class="fill content">
                            <textarea type="text" class="@error('con') is-invalid @enderror" name="con" placeholder="Nội dung ... ">{{old('con')}}</textarea>
                            <span class="icon"><i class="fa fa-pencil"></i></span>
                            @error('con')
                            <i style="color:  red">{{$message}}</i>
                            @enderror
                        </div>

                        <button  class="send-contact" type="submit">Gửi đi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection