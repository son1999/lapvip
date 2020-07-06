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
                    <i class="fe-menu"></i>THÔNG TIN CƠ BẢN
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Tên Nhóm</label>
                                <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old('title') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Độ ưu tiên</label>
                                <input type="text" class="form-control{{ $errors->has('rank') ? ' is-invalid' : '' }}" id="rank" name="rank" value="{{ old('rank') }}" onkeypress="return shop.numberOnly()" maxlength="4" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach ($roles as $key => $role)
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-header"><b>{{ $role['title'] }}</b></div>
                            <div class="card-body">
                                @foreach ($role['perm'] as $k => $title)
                                    <div class="row">
                                        <div class="col-sm-8">{{ $title }}</div>
                                        <div class="col-sm-4">
                                            <label class="switch switch-text switch-pill switch-success">
                                                <input type="checkbox" class="switch-input" name="{{$key}}[]" value="{{ $k }}">
                                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                                <span class="switch-handle"></span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
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