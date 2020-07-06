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

            <div class="card">
                <div class="card-header">
                    <i class="fe-menu"></i>THÔNG TIN
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="number">Title</label>
                                <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old_blade('title') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="lang">Ngôn ngữ</label>
                                <select id="lang" name="lang" class="form-control{{ $errors->has('lang') ? ' is-invalid' : '' }}" onchange="shop.admin.getCat(2, $('#cat_id').val(), this.value, '#cat_id')">
                                    @foreach($langOpt as $k => $v)
                                        <option value="{{ $k }}" @if(old('lang') == $k) selected="selected" @endif>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="lang">Chọn Mã Giảm Giá (nếu có)</label>
                                <select id="coup_id" name="coup_id" class="form-control">
                                    <option value="" selected>-- Mã giảm giá --</option>
                                    @foreach($coupons as $v)
                                        <option value="{{ $v->id }}" {{old_blade('coup_id') == $v->id ? 'selected' : '' }}>Mã:  {{$v->code}}  | Giảm: {{ $v->value }} @if($v->value > 100 ) đ @else % @endif</option>
                                    @endforeach
                                </select>
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
@section('js_bot')
    <script type="text/javascript">
        shop.ready.add(function(){
            shop.admin.getCat(2, {{ old('cat_id', 0) }}, '{{ old('lang', '') }}', '#cat_id');
        }, true);
    </script>
@stop
