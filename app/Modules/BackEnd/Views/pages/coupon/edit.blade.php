@extends('BackEnd::layouts.default')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @if(old_blade('editMode'))
                {!! Form::open(['url' => route('admin.'.$key.'.edit.post', old_blade('id')) , 'files' => true]) !!}
            @else
                {!! Form::open(['url' => route('admin.'.$key.'.add.post') , 'files' => true]) !!}
            @endif

            @if( count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{!! $error !!}</div>
                    @endforeach
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success">
                    {!! session('status') !!}
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
                                <label for="number">Mã giảm giá</label>
                                <input type="text" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" id="code" name="code" value="{{ old_blade('code') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="value">Giảm (<100 thì tính %)</label>
                                <input type="text" class="form-control{{ $errors->has('value') ? ' is-invalid' : '' }}" id="value" name="value" value="{{ old_blade('value') }}" required>
                            </div>
                        </div>
                    </div>
                    {{--<div class="row">--}}
                    {{--<div class="col-sm-6">--}}
                    {{--<div class="form-group">--}}
                    {{--<label for="started">Ngày kích hoạt</label>--}}
                    {{--<input type="text" class="form-control{{ $errors->has('started') ? ' is-invalid' : '' }}" id="started" name="started" value="{{ old('started') }}" required autocomplete="off">--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="expired">Ngày hết hạn</label>
                                <input type="text" class="form-control{{ $errors->has('expired') ? ' is-invalid' : '' }}" id="expired" name="expired" value="{{ \Lib::dateFormat(old_blade('expired'),  'd/m/Y H:i') }}" required autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="expired">Số lần áp dụng</label>
                                <input type="text" class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}" id="quantity" name="quantity" value="{{ old_blade('quantity') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="type">Áp dụng cho</label>
                                <select onchange="changeAplly(this)" id="type" name="type" class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}">
                                    @foreach($types as $k => $v)
                                        <option value="{{ $k }}" @if(old_blade('type') == $k) selected="selected" @endif>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                        <div class="row wrap-object-id" style="{{old_blade('type') == 'order' ? 'display:none;' : '' }}">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="object_id">ID đối tượng áp dụng ( Danh mục, sản phẩm) Cách nhau dấu ,</label>
                                <input type="object_id" class="form-control{{ $errors->has('object_id') ? ' is-invalid' : '' }}" id="object_id" name="object_id" value="{{ old_blade('object_id') }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Cập nhật</button>
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

        function changeAplly(ele) {
            var value = $(ele).val();
            if(value != 'order') {
                $('.wrap-object-id').show();
            }else {
                $('.wrap-object-id').hide();
            }
        }

    </script>
@stop
