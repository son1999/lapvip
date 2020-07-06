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
                            <span class="input-group-addon"><i class="fa fa-cash-register"></i></span>
                            <select id="filter_cate_id" name="filter_cate_id"  class="select2 form-control{{ $errors->has('filter_cate_id') ? ' is-invalid' : '' }}">
                                <option value="">-- Chủng loại hạng mục --</option>
                                @foreach($filters_cate_id as $k => $v)
                                    <option {{$request->filter_cate_id == $v['id'] ? 'selected' : ''}} class="filter_cate" value="{{ old('title', $v['id']) }}">
                                        {{ $v['title'] }}
                                    </option>
                                    <option class="text-danger" disabled>{{$v['desc']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
{{--                    <div class="form-group col-sm-3">--}}
{{--                        <div class="input-group">--}}
{{--                            <span class="input-group-addon"><i class="fa fa-language"></i></span>--}}
{{--                            <select id="lang" name="lang" class="form-control">--}}
{{--                                <option value="">-- Chọn ngôn ngữ --</option>--}}
{{--                                @foreach($langOpt as $k => $v)--}}
{{--                                    <option value="{{ $k }}" @if($search_data->lang == $k) selected="selected" @endif>{{ $v }}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    {{-- <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-list"></i></span>
                            <select id="status" name="status" class="form-control">
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="2"{{ $search_data->status == 1 ? ' selected="selected"' : '' }}>Đang hiển thị</option>
                                <option value="1"{{ $search_data->status == 2 ? ' selected="selected"' : '' }}>Đang ẩn</option>
                                <option value="-1"{{ $search_data->status == -1 ? ' selected="selected"' : '' }}>Đã xóa</option>
                            </select>
                        </div>
                    </div> --}}
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
                <table class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr>
                        <th width="55">ID</th>
                        <th>Hạng mục sản phẩm</th>
                        <th>Chủng loại hạng mục</th>
                        @if(\Lib::can($permission, 'edit') || \Lib::can($permission, 'delete'))
                            <th width="55" class="text-center">Lệnh</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                       @foreach ($data as $k => $item)
                        <tr>
                            <td>{{++$k}}</td>
                            <td>{{$item['title']}}</td>
                            <td>
                               @if($item->pid != 0){{$item->getFilterByID($item->pid)->title_fil}} - @endif{{$item->getFiltecate($item->filter_cate_id)->title}}

                            </td>
                            <td align="center">
                                @if(\Lib::can($permission, 'edit'))
                                    @if($item->is_far == 1)
                                        <a href="javascript:void(0)" class="text-primary" onclick="shop.admin.updateFar({{ $item->id }},false,'filter')" title="Đang hiển thị, Click để ẩn"><i class="fe-home"></i></a>
                                    @else
                                        <a href="javascript:void(0)" class="text-secondary" onclick="shop.admin.updateFar({{ $item->id }}, true,'filter')" title="Đang ẩn, Click để hiển thị"><i class="fe-home"></i></a>
                                    @endif
                                    <a href="{{ route('admin.'.$key.'.edit', $item->id) }}" class="btn text-primary"><i class="fe-edit"></i></a>
                                @endif
                                @if(\Lib::can($permission, 'delete'))
                                    <a href="{{ route('admin.'.$key.'.delete', $item->id) }}"  class="btn text-danger" onclick="return confirm('Bạn muốn xóa ?')"><i class="icon-trash icons"></i></a>
                                @endif
                            </td>
                        </tr>
                       @endforeach
                    </tbody>
                </table>

                @if(empty($data) || $data->isEmpty())
                    <h4 align="center">Không tìm thấy dữ liệu phù hợp</h4>
                @else
                    <div class="pull-right">Tổng cộng: {{ $data->count() }} bản ghi / {{ $data->lastPage() }} trang</div>
                    {!! $data->links('BackEnd::layouts.pagin') !!}
                @endif
            </div>
        </div>
    </div>
</div>
@stop
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-results__option[aria-disabled=true] {
            font-size: 12px;
            margin-left: 20px;
            color: red;
            padding-top: 0;
        }

    </style>
@stop
@section('js_bot')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
    <script>
        $("#filter_cate_id").select2();
        shop.getMenu = function (type, lang, def) {
            var html = '<option value="0">-- Chọn --</option>';
            shop.ajax_popup('menu/get-menu', 'POST', {type:type, lang:lang}, function(json) {
                $.each(json.data,function (ind,value) {
                    html += '<option value="'+value.id+'"'+(def == value.id?' selected':'')+'>'+value.title+'</option>';
                    if(value.sub.length != 0){
                        $.each(value.sub,function (k,sub) {
                            html += '<option value="'+sub.id+'"'+(def == sub.id?' selected':'')+'> &nbsp;&nbsp;&nbsp; '+sub.title+'</option>';
                        });
                    }
                });
                $('#pid').html(html);
            });
        };
    </script>
@stop