@extends('BackEnd::layouts.default')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            {!! Form::open(['url' => route('admin.'.$key.'.add.post'), 'files' => true]) !!}

            @if( count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{!! $error !!}</div>
                    @endforeach
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <i class="fe-menu"></i>THÔNG TIN
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="number">Số lượng mã</label>
                                <input type="text" class="form-control{{ $errors->has('number') ? ' is-invalid' : '' }}" id="number" name="number" value="{{ old('number') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="value">Giảm giá (%)</label>
                                <input type="text" class="form-control{{ $errors->has('value') ? ' is-invalid' : '' }}" id="value" name="value" value="{{ old('value') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="expired">Ngày hết hạn</label>
                                <input type="text" class="form-control{{ $errors->has('expired') ? ' is-invalid' : '' }}" id="expired" name="expired" value="{{ old('expired') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="expired">Ngày kích hoạt</label>
                                <input type="text" class="form-control{{ $errors->has('actived') ? ' is-invalid' : '' }}" id="actived" name="actived" value="{{ old('actived') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="expired">Số lần sử dụng</label>
                                <input type="number" class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}" id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="type">Áp dụng cho</label>
                                <select id="type" name="type" class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}">
                                    @foreach($types as $k => $v)
                                        <option value="{{ $k }}" @if(old('type') == $k) selected="selected" @endif>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
{{--                    <div class="row">--}}
{{--                        <div class="col-sm-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="lang">Ngôn ngữ</label>--}}
{{--                                <select id="lang" name="lang" class="form-control{{ $errors->has('lang') ? ' is-invalid' : '' }}">--}}
{{--                                    @foreach($langOpt as $k => $v)--}}
{{--                                        <option value="{{ $k }}" @if(old('lang') == $k) selected="selected" @endif>{{ $v }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Thêm mới</button>
                &nbsp;&nbsp;
                <a class="btn btn-sm btn-danger" href="{{ redirect()->back()->getTargetUrl() }}"><i class="fa fa-ban"></i> Hủy bỏ</a>
            </div>
            {!! Form::close() !!}
        </div>

    </div>
@stop
@section('css')
<link rel="stylesheet" href="{{ asset('admin/css/jquery.datetimepicker.min.css') }}?ver={{$def['version']}}">
@stop
@section('js_bot')
    <script type="text/javascript" src="{{ asset('admin/js/library/jquery.datetimepicker.min.js') }}?ver={{$def['version']}}"></script>
    <script>
        $('#expired').datetimepicker({
            format:'d/m/Y H:i',
        });
        $('#actived').datetimepicker({
            format:'d/m/Y H:i',
        });
    </script>
@stop
