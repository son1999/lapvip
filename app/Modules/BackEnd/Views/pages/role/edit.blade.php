@extends('BackEnd::layouts.default')

@section('content')
    <div class="row">
        @if ( $data->id == 1 || !\Auth::user()->checkMyRank($data->rank))
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Cảnh báo!</h4>
                <p>Vì lí do bảo mật nên bạn không thể chỉnh sửa thông tin của <b>{{ $data->title }}</b></p>
                <hr>
                <p class="mb-0" align="right">
                    <a class="btn btn-outline-warning" href="{{ redirect()->back()->getTargetUrl() }}"><i class="fa fa-angle-left"></i>&nbsp; Quay lại</a>
                </p>
            </div>
        @else
        <div class="col-sm-12">
            {!! Form::open(['url' => route('admin.'.$key.'.edit.post', $data->id), 'files' => true ]) !!}

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
                    <i class="fe-menu-square-o"></i> SỬA THÔNG TIN
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Tên Nhóm</label>
                                <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old('title', $data->title) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Độ ưu tiên</label>
                                <input type="text" class="form-control{{ $errors->has('rank') ? ' is-invalid' : '' }}" id="rank" name="rank" value="{{ old('rank', $data->rank) }}" onkeypress="return shop.numberOnly()" maxlength="4" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                @php($data->permit = json_decode($data->permit, true))
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
                                            <input type="checkbox" class="switch-input" name="{{$key}}[]" value="{{ $k }}" @if(isset($data->permit[$key]) && in_array($k, $data->permit[$key]))) checked="checked" @endif>
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
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Cập nhật</button>
                &nbsp;&nbsp;
                <a class="btn btn-sm btn-danger" href="{{ redirect()->back()->getTargetUrl() }}"><i class="fa fa-ban"></i> Hủy bỏ</a>
            </div>
            {!! Form::close() !!}
        </div>
        @endif
    </div>
@stop