@extends('BackEnd::layouts.default')

@section('content')
    <div class="row">
        <div class="col-sm-6">
            {!! Form::open(['url' => route('admin.'.$key.'.add.post')]) !!}
            <div class="card">
                <div class="card-header">
                    <i class="fe-menu"></i>THÔNG TIN
                </div>
                <div class="card-body">
                    @if( count($errors) > 0)
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{!! $error !!}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="fullname">Họ tên</label>
                                <input type="text" class="form-control{{ $errors->has('fullname') ? ' is-invalid' : '' }}" id="fullname" name="fullname" value="{{ old('fullname') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="title">Email</label>
                                <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="phone">Điện thoại</label>
                                <input type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" id="phone" name="phone" value="{{ old('phone') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Thêm mới</button>
                    &nbsp;&nbsp;
                    <a class="btn btn-sm btn-danger" href="{{ redirect()->back()->getTargetUrl() }}"><i class="fa fa-ban"></i> Hủy bỏ</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop