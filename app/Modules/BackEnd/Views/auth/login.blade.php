@extends('BackEnd::auth.layout')

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop
@section('content')
    <div class="col-md-8">
        <div class="card-group mb-0">
            <div class="card p-4">
                <div class="card-body">
                    <h1 class="mb-4">Đăng nhập</h1>

                    @if ($errors->has('user_name'))
                        <p class="text-danger">{{ $errors->first('user_name') }}</p>
                    @endif

                    @if ($errors->has('password'))
                        <p class="text-danger">{{ $errors->first('password') }}</p>
                    @endif

                    {!! Form::open(['url' => route('login')]) !!}
                    <div class="input-group mb-3">
                        <span class="input-group-addon"><i class="icon-user"></i></span>
                        <input type="text" class="form-control{{ $errors->has('user_name') ? ' is-invalid' : '' }}" placeholder="Tên đăng nhập" name="user_name" value="{{ old('user_name') }}" required autofocus>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-addon"><i class="icon-lock"></i></span>
                        <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Mật khẩu" name="password" required>
                    </div>

                    <div class="input-group mb-4">
                        <input class="form-check-input" type="checkbox" id="remember_pass" style="margin-left: 0" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember_pass">Ghi nhớ đăng nhập</label>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary px-4">Đăng nhập</button>
                        </div>
                        <div class="col-6 text-right">
                            <a class="btn btn-link" href="{{ route('password.request') }}">Quên mật khẩu?</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            {{--<div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">--}}
                {{--<div class="card-body text-center">--}}
                    {{--<div>--}}
                        {{--<h2>Đăng kí</h2>--}}
                        {{--<p>Chức năng hiện tại không được hỗ trợ.<br /> Vui lòng liên hệ với ban quản trị website để đăng kí tài khoản.</p>--}}
                        {{--<a class="btn btn-primary active mt-3" href="{{ route('register') }}">Gửi yêu cầu</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>
@stop
