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
                                <label for="link">Thể Loại</label>
                                <select id="type" name="type" class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}">
                                    <option value="" selected disabled>--Chọn Loại Banner Hiển Thị--</option>
                                    <option value="1" @if(old_blade('type') == 1) selected="selected" @endif>Hình Ảnh</option>
                                    <option value="2" @if(old_blade('type') == 2) selected="selected" @endif>Video</option>
                                </select>
                            </div>
                        </div>
                    </div>
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
                                <label for="title">Link</label>
                                <input type="text" class="form-control{{ $errors->has('link') ? ' is-invalid' : '' }}" id="link" name="link" value="{{ old_blade('link') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="link">Ngôn ngữ</label>
                                <select id="lang" name="lang" class="form-control{{ $errors->has('lang') ? ' is-invalid' : '' }}">
                                    @foreach($langOpt as $k => $v)
                                        <option value="{{ $k }}" @if(old_blade('lang') == $k) selected="selected" @endif>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Vị trí hiển thị</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                @php($positions = old_blade('positions'))
                                @foreach($options as $k => $r)
                                    <div>
                                        <label for="checkbox{{ $k }}">
                                            <input type="checkbox" id="checkbox{{ $k }}" name="positions[]" value="{{ $k }}" @foreach(explode(',', $positions) as $item_po) {{$item_po == $k ? 'checked' : ''}}@endforeach >&nbsp; {{ $r }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="card">
                    <div class="card-header">Danh mục hiển thị</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="code">Danh mục</label>
                                    <select  id="cat_id" name="cat_id" class="form-control{{ $errors->has('cat_id') ? ' is-invalid' : '' }}">
                                        <option value="" selected disabled>-- Chọn danh mục --</option>
                                        @foreach($catOpt as $i_cat)
                                            <option value="{{$i_cat['id']}}" name="cat_id" id="cat_id" {{old_blade('cat_id') == $i_cat['id'] ? 'selected' : ''}}>{{$i_cat['title']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="card">
                <div class="card-header">
                    <i class="fa fa-image"></i>ẢNH
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="file" id="image" name="image">
                            @if(!empty($data->image))
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
