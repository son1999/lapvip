@extends('BackEnd::layouts.default')

@section('content')
    <div class="row">
        <div class="col-sm-6">
            {{--{!! Form::open(['url' => route('admin.'.$key.'.profile.post')]) !!}--}}
            {!! Form::open(['url' => route('admin.'.$key.'.edit.post', $data->id), 'files' => true ]) !!}
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-user"></i>Sửa thông tin cá nhân
                </div>
                <div class="card-body">
                    @if( count($errors) > 0)
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div> {!! $error !!} </div>
                            @endforeach
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success">
                            {!! session('status') !!}
                        </div>
                    @endif

                    <input type="hidden" name="editProfile" value="1" />
                    <input type="hidden" name="active" value="3" />

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="fullname">Họ và tên</label>
                                <input type="text" class="form-control{{ $errors->has('fullname') ? ' is-invalid' : '' }}" id="fullname" name="fullname" value="{{ old('fullname', $data->fullname) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="email">Email </label>
                                <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" value="{{ old('email', $data->email) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="phone">Số điện thoại </label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $data->phone) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="phone">Ảnh đại diện</label>
                                <input type="file" class="form-control" id="image" name="image" required>
                                @if(!empty($data->image))
                                    <div class="pull-right">
                                        <img src="{{ $data->getImageAvatar('small') }}" class="w-100" />
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Cập nhật</button>
                    <a class="btn btn-sm btn-danger" href="{{ redirect()->back()->getTargetUrl() }}"><i class="fa fa-ban"></i> Hủy bỏ</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop