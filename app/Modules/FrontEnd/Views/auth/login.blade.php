@extends('FrontEnd::layouts.home')

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop

@section('content')
    <form action="{{route('login.post')}}" method="post">
        @csrf
        <div  role="dialog" aria-labelledby="exampleModalLabel" >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header flex-column align-items-center text-center border-0">
                        <h5 class="modal-title">Đăng nhập</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="pop-phone-login">Email</label>
                            <input name="email" type="text" class="form-control" placeholder="{{ __('Email đã đăng ký') }}">
                        </div>
                        <div class="form-group">
                            <label for="">Nhập mật khẩu</label>
                            <input name="password" type="password" class="form-control" placeholder="{{ __('auth.matkhau') }}">
                        </div>
                        <div class="d-flex flex-md-nowrap flex-wrap justify-content-between mb-3">
                            <div class="remember-pass">
                                <input type="checkbox" name="rememberLogin">
                                <span>Ghi nhớ đăng nhập </span>
                            </div>
                            <a href="{{ route('password') }}" data-dismiss="modal" data-toggle="modal" data-target="#forgetpass">Quên mật khẩu?</a>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-theme">ĐĂNG NHẬP</button>
                        </div>
                        <p class="text-center">Bạn chưa có tài khoản? <a href="javascript:;" data-dismiss="modal" data-toggle="modal" data-target="#singup">Đăng ký tài khoản</a></p>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

