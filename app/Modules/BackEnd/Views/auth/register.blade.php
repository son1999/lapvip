@extends('BackEnd::auth.layout')

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop
@section('content')
    <div class="col-md-6">
        {!! Form::open(['url' => route('register')]) !!}
        <div class="card mx-4">
            <div class="card-body p-4">
                <h1>Đăng kí</h1>
                <p class="text-muted">Đăng kí tài khoản Quản trị {{ env('APP_NAME') }}</p>

                @if( count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{!! $error !!}</div>
                        @endforeach
                    </div>
                @endif

                <div class="input-group mb-3">
                    <span class="input-group-addon"><i class="icon-user"></i></span>
                    <input type="text" class="form-control{{ $errors->has('user_name') ? ' is-invalid' : '' }}" placeholder="Tên đăng nhập" name="user_name" value="{{ old('user_name') }}" required autofocus>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-addon">@</span>
                    <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-addon"><i class="icon-lock"></i></span>
                    <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Mật khẩu" name="password" required>
                </div>

                <div class="input-group mb-4">
                    <span class="input-group-addon"><i class="icon-lock"></i></span>
                    <input type="password" class="form-control" placeholder="Nhập lại mật khẩu" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-block btn-success">Đăng kí</button>

                <div class="mt-4"><span class="text-danger">Đã có tài khoản?</span> <a href="{{ route('login') }}">Đăng nhập</a></div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@stop