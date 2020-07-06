@extends('FrontEnd::layouts.default')

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop

@section('content')



            <div class="col-10 col-lg-4 user-tab-main" style=" margin: auto!important; margin-top: 30px!important;">
                <div class="col-12 col-lg-12 user-tab-main">
                    <div class="form-content user-info-pass">
                        <h4 class="form-title" style="text-align: center">Thay đổi mật khẩu</h4>
                        @if($token)
                            <div style="margin-bottom: 150px!important;">
                                {!! Form::open(['url' => route('password.reset.post')]) !!}
                                <div class="info-pass-wrap">
                                    <input type="hidden" value="{{ $token->token }}" name="token">
                                    <div class="user-info-item">
                                        <label for="">Email</label>
                                        <div style="flex-grow: 1;">
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror">
                                            @error('email')
                                            <i style="color:  red">{{$message}}</i>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="user-info-item">
                                        <label for="">Nhập mật khẩu mới</label>
                                        <div style="flex-grow: 1;">
                                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                            @error('password')
                                            <i style="color:  red">{{$message}}</i>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="user-info-item">
                                        <label for="">Nhập lại mật khẩu mới</label>
                                        <div style="flex-grow: 1;">
                                            <input type="password" name="password_confirm" class="form-control @error('password_confirm') is-invalid @enderror">
                                            @error('password_confirm')
                                            <i style="color:  red">{{$message}}</i>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-gr btn-gr-user mt-3" style="float: right">
                                    <button type="submit" class="btn btn-success">Đổi mật khẩu</button>
                                </div>
                                {!! Form::close() !!}
                            </div>

                        @else
                            <div class="alert alert-danger">
                                Yêu cầu của bạn không hợp lệ hoặc đã bị quá hạn. Vui lòng <a href="{{ route('password') }}"><b>thực hiện lại</b></a> thao tác.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

@stop
