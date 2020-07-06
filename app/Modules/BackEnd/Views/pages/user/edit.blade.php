@extends('BackEnd::layouts.default')

@section('content')
    <div class="row">
    @if ( $data->isRoot() || !\Auth::user()->biggerThanYou($data->id))
        <div class="alert alert-warning" role="alert">
            <h4 class="alert-heading">Cảnh báo!</h4>
            <p>Vì lí do bảo mật nên bạn không thể chỉnh sửa thông tin cá nhân của <b>{{ $data->user_name }}</b></p>
            <hr>
            <p class="mb-0" align="right">
                <a class="btn btn-outline-warning" href="{{ redirect()->back()->getTargetUrl() }}"><i class="fa fa-angle-left"></i>&nbsp; Quay lại</a>
            </p>
        </div>
    @else
        <div class="col-sm-6">
            {!! Form::open(['url' => route('admin.'.$key.'.edit.post', $data->id), 'files' => true]) !!}
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-user"></i>Sửa thông tin người dùng <b>{{ $data->user_name }}</b>
                </div>
                <div class="card-body">
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

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="password">Mật khẩu </label>
                                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="password_confirm">Nhập lại mật khẩu </label>
                                <input type="password" class="form-control{{ $errors->has('password_confirm') ? ' is-invalid' : '' }}" id="password_confirm" name="password_confirm">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="fullname">Họ và tên</label>
                                <input type="text" class="form-control{{ $errors->has('fullname') ? ' is-invalid' : '' }}" id="fullname" name="fullname" value="{{ old('fullname', $data->fullname) }}">
                                <input type="hidden" value="{{ $data->id  }}" name="user_id">
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
                                <input type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" id="phone" name="phone" value="{{ old('phone', $data->phone) }}" required maxlength="11">
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

                    <div class="card">
                        <div class="card-header">Phân quyền</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        @foreach($roles as $key => $r)
                                            <div class="checkbox checkbox-info" >
                                                <input type="checkbox" class="checkbox" id="role-{{$key}}" name="roles[]" value="{{ $r->id }}"{{ $user_roles->contains('rid', $r->id)?' checked':'' }}{{ !\Auth::user()->checkMyRank($r->rank)?' disabled':'' }}>
                                                <label for="role-{{$key}}" class="ml-2">
                                                    &nbsp; {{ $r->title }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
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
    @endif
    </div>
@stop