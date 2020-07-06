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
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="time_from" class="datepicker form-control" placeholder="Ngày tạo từ" autocomplete="off" value="{{ $search_data->time_from }}">
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="time_to" class="datepicker form-control" placeholder="Ngày tạo đến" autocomplete="off" value="{{ $search_data->time_to }}">
                        </div>
                    </div>
                </div>
                <div class="row">
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
                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-list"></i></span>
                            <select name="status" class="form-control">
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="2"{{ $search_data->status == 2 ? ' selected="selected"' : '' }}>Đang hiển thị</option>
                                <option value="1"{{ $search_data->status == 1 ? ' selected="selected"' : '' }}>Đang ẩn</option>
                                <option value="-1"{{ $search_data->status == -1 ? ' selected="selected"' : '' }}>Đã xóa</option>
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
                <table class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr>
                        <th width="55">ID</th>
                        <th>Tiêu đề</th>
                        <th width="120">Ngôn ngữ</th>
                        <th width="100">Ngày tạo</th>
                        @if(\Lib::can($permission, 'edit') || \Lib::can($permission, 'delete'))
                            <th width="55">Lệnh</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td align="center">{{ $item->id }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->lang() }}</td>

                        <td align="center">
                            <samll style="font-size: 12px; padding: 0 0.35rem;" class="btn btn-success text-white btn-xs waves-effect waves-light">{{ \Lib::dateFormat($item->created, 'd/m/Y') }}</samll>
                        </td>
                        <td align="center">
                            @if(\Lib::can($permission, 'edit'))
                                @if($item->status == 2)
                                    <a href="javascript:void(0)" class="text-light btn btn-primary" onclick="shop.admin.updateStatus({{ $item->id }},false,'story_wt')" title="Đang hiển thị, Click để ẩn"><i class="fe-check-circle"></i></a>
                                @else
                                    <a href="javascript:void(0)" class="text-light btn btn-secondary" onclick="shop.admin.updateStatus({{ $item->id }}, true,'story_wt')" title="Đang ẩn, Click để hiển thị"><i class="fe-check-circle"></i></a>
                                @endif
{{--                                @if($item->is_banner == 2)--}}
{{--                                    <a href="javascript:void(0)" class="text-light btn btn-success mt-3" onclick="shop.admin.updateStatusBanner({{ $item->id }},false,'news')" title="Đang hiển thị banner, Click để ẩn"><i class="fe-image"></i></a>--}}
{{--                                @else--}}
{{--                                    <a href="javascript:void(0)" class="text-light btn btn-secondary mt-3" onclick="shop.admin.updateStatusBanner({{ $item->id }}, true,'news')" title="Đang ẩn, Click để hiển thị banner"><i class="fe-image"></i></a>--}}
{{--                                @endif--}}
                                <a href="{{ route('admin.'.$key.'.edit', $item->id) }}" class="btn my-3 text-light btn-primary"><i class="fe-edit"></i></a>
                            @endif
                            @if(\Lib::can($permission, 'delete'))
                                <a href="{{ route('admin.'.$key.'.delete', $item->id) }}"  class="btn text-light btn-danger" onclick="return confirm('Bạn muốn xóa ?')"><i class="icon-trash icons"></i></a>
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