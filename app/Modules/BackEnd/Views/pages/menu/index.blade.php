@extends('BackEnd::layouts.default')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="mb-5"><h1>Quản trị {{ $site_title }}</h1></div>

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

        {!! Form::open(['url' => route('admin.'.$key), 'method' => 'get', 'id' => 'searchForm']) !!}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fe-bookmark"></i></span>
                            <input type="text" name="title" class="form-control" placeholder="Tiêu đề" value="{{ $search_data->title }}">
                        </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-language"></i></span>
                            <select id="lang" name="lang" class="form-control">
                                <option value="">-- Chọn ngôn ngữ --</option>
                                @foreach($langOpt as $k => $v)
                                    <option value="{{ $k }}" @if($search_data->lang == $k) selected="selected" @endif>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
            </div>
        </div>
        {!! Form::close() !!}

        <div class="card card-accent-info">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Danh sách
            </div>
            <div class="card-body">
                <table class="table table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th width="55">Sort</th>
                            <th width="65">Cấp 1</th>
                            <th width="65">Cấp 2</th>
                            <th width="65">Cấp 3</th>
                            <th>Tiêu đề</th>
                            <th>Link</th>
                            <th>Perm</th>
                            <th>NewTab</th>
                            <th>Follow</th>
                            <th width="120">Ngôn ngữ</th>
                            <th width="100">Ngày tạo</th>
                            @php($cols = 11)
                            @if(\Lib::can($permission, 'edit'))
                                @php($cols++)
                                <th width="55">Sửa</th>
                            @endif
                            @if(\Lib::can($permission, 'delete'))
                                @php($cols++)
                                <th width="55">Xóa</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                        <tr class="bg-primary">
                            <td colspan="{{$cols}}" class="text-uppercase"><b>{{ $item['title'] }} - {{ $item['type'] }}</b></td>
                        </tr>
                        @foreach ($item['menus'] as $menu)
                            @include('BackEnd::pages.menu.list', [
                                'd' => $menu['data'],
                                'sort_col' => 1,
                                'title_col' => 4,
                                'class' => 'alert-success'
                            ])
                            @if(!empty($menu['sub']))
                                @foreach ($menu['sub'] as $sub1)
                                    @include('BackEnd::pages.menu.list', [
                                        'd' => $sub1['data'],
                                        'sort_col' => 2,
                                        'title_col' => 3,
                                        'class' => 'alert-warning'
                                    ])
                                    @if(!empty($sub1['sub']))
                                        @foreach ($sub1['sub'] as $sub2)
                                            @include('BackEnd::pages.menu.list', [
                                                'd' => $sub2,
                                                'sort_col' => 3,
                                                'title_col' => 2,
                                                'class' => 'text-danger'
                                            ])
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--/.col-->
</div>
@stop