@extends('FrontEnd::layouts.default')

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop

@section('content')
    <div id="content" class="section content-wrap content-login">
        <div class="container clearfix">
            <div class="login-wrapper">
                <div class="clearfix">
                    <div class="login-l make-left">
                        <div class="login-form-title fw-rbm fs-22 fc-black">{{ __('auth.laylaimatkhau') }}</div>
                        <div class="popup-olala-login">
                            <div class="popup-form">
                                @if( count($errors) > 0)
                                    <div class="row-err">
                                        @foreach ($errors->all() as $error)
                                            <div class="err-notice fs-14">{!! $error !!}</div>
                                        @endforeach
                                    </div>
                                @endif

                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                {!! Form::open(['url' => route('password.post')]) !!}
                                <div class="popup-form-row @if($errors->has('email')) popup-form-row-err @endif">
                                    <label for="pop-email-form"><i class="icons iEmail3"></i></label>
                                    <input id="pop-email-form" class="pop-input" type="text" placeholder="{{ __('auth.email') }}" name="email" value="{{ old('email') }}">
                                </div>
                                <div class="popup-form-btn">
                                    <button class="fs-16 fw-rbb">{{ __('auth.laylaimatkhaulink') }}</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
