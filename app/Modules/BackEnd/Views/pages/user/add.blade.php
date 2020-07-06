@extends('BackEnd::layouts.default')

@section('content')
    <div class="row">
        <div class="col-sm-6">
            {!! Form::open(['url' => route('admin.'.$key.'.add.post'), 'files' => true]) !!}
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-user"></i>Thêm người dùng mới
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
                                <label for="fullname">Tên đăng nhập</label>
                                <input type="text" class="form-control{{ $errors->has('user_name') ? ' is-invalid' : '' }}" id="user_name" name="user_name" value="{{ old('user_name') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="password">Mật khẩu </label>
                                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="password_confirm">Nhập lại mật khẩu </label>
                                <input type="password" class="form-control{{ $errors->has('password_confirm') ? ' is-invalid' : '' }}" id="password_confirm" name="password_confirm" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="fullname">Họ và tên</label>
                                <input type="text" class="form-control{{ $errors->has('fullname') ? ' is-invalid' : '' }}" id="fullname" name="fullname" value="{{ old('fullname') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="email">Email </label>
                                <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="phone">Số điện thoại </label>
                                <input type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" id="phone" name="phone" value="{{ old('phone') }}" required onkeydown="return shop.numberOnly()" maxlength="11">
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
                                        @php($user_roles = old('roles', []))
                                        @foreach($roles as $r)
                                            <div class="checkbox">
                                                <label for="checkbox{{ $r->id }}">
                                                    <input type="checkbox" id="checkbox{{ $r->id }}" name="roles[]" value="{{ $r->id }}"{{ in_array($r->id, $user_roles)?' checked':'' }}{{ !\Auth::user()->checkMyRank($r->rank)?' disabled':'' }}>&nbsp; {{ $r->title }}
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
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Thêm mới</button>
                    <a class="btn btn-sm btn-danger" href="{{ redirect()->back()->getTargetUrl() }}"><i class="fa fa-ban"></i> Hủy bỏ</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop