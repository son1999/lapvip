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
                    <i class="fe-menu-square-o"></i>  SỬA THÔNG TIN
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Tiêu đề</label>
                                <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old_blade('title') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="layer">Chọn layer nhóm</label>
                                <select id="layer" name="layer" class="form-control{{ $errors->has('layer') ? ' is-invalid' : '' }}">
                                    <option value="-1">-- Chọn --</option>
                                    @foreach($layers as $k => $v)
                                        <option value="{{ $k }}"{{ $k == old_blade('layer') ? ' selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Độ ưu tiên</label>
                                <input type="text" class="form-control{{ $errors->has('sort') ? ' is-invalid' : '' }}" id="sort" name="sort" value="{{ old_blade('sort') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="radio">
                                <label for="radio1">
                                    <input type="radio" id="radio1" name="type" value="percent" @if(old_blade('percent') > 0) checked="checked" @endif> {{ \App\Models\CustomerGroup::options(0) }}
                                </label>

                                <div class="row" @if(old_blade('percent') <= 0) style="display: none" @endif>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control{{ $errors->has('percent') ? ' is-invalid' : '' }}" id="percent" name="percent" value="{{ old_blade('percent') }}" maxlength="3" onkeypress="return shop.numberOnly()" onfocus="this.select()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--<div class="radio">--}}
                                {{--<label for="radio2">--}}
                                    {{--<input type="radio" id="radio2" name="type" value="bonus" @if(old_blade('bonus') > 0) checked="checked" @endif> {{ \App\Models\CustomerGroup::options(1) }}--}}
                                {{--</label>--}}

                                {{--<div class="row" @if(old_blade('bonus') <= 0) style="display: none" @endif>--}}
                                    {{--<div class="col-sm-3">--}}
                                        {{--<div class="form-group">--}}
                                            {{--<input type="text" class="form-control{{ $errors->has('bonus') ? ' is-invalid' : '' }}" id="bonus" name="bonus" value="{{ old_blade('bonus') }}" onkeypress="return shop.numberOnly()" onfocus="this.select()">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fa fa-image"></i>ẢNH ĐẠI DIỆN
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="file" id="image" name="image">
                            @if(isset($data->image) && !empty($data->image))
                                <div class="pull-right">
                                    <img src="{{ $data->getImageUrl('small') }}" />
                                </div>
                            @endif
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
    {!! \Lib::addMedia('admin/js/library/chosen/chosen.min.css') !!}
@stop

@section('js_bot')
    {!! \Lib::addMedia('admin/js/library/chosen/chosen.jquery.min.js') !!}
    <script>
        if($('input:radio[name=type]').length > 0) {
            $('input:radio[name=type]').change(function () {
                $('.radio .row').hide();
                $('.row', $(this).parents('.radio')).show();
            });

            $('input:checkbox[name=except]').change(function () {
                $('.row', $(this).parents('.checkboxok')).slideToggle();
            });
        }
    </script>
@stop