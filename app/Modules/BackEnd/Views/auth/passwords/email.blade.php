@extends('BackEnd::auth.layout')

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop
@section('content')
    <div class="col-md-6">
        {!! Form::open(['url' => route('password.email')]) !!}
        <div class="card mx-4">
            <div class="card-body p-4">
                <h1>Lấy lại mật khẩu</h1>
                <p class="text-muted">Vui lòng cung cấp địa chỉ Email đã dùng để đăng kí</p>

                @if( count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{!! $error !!}</div>
                        @endforeach
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif


                <div class="input-group mb-3">
                    <span class="input-group-addon">@</span>
                    <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Email" name="email" value="{{ old('email') }}" required>
                </div>

                <button type="submit" class="btn btn-block btn-success">Gửi Link lấy lại mật khẩu</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
