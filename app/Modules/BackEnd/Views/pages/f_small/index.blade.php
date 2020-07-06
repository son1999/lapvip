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
                    <div class="form-group col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fe-bookmark"></i></span>
                            <input type="text" name="title" class="form-control" placeholder="Tiêu đề" value="{{ $search_data->title }}">
                        </div>
                    </div>

                    <div class="form-group col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                            <select id="position" name="position" class="form-control">
                                <option value="">-- Phân loại --</option>
                                @foreach($options as $k => $v)
                                    <option value="{{ $k }}" @if($search_data->position == $k) selected="selected" @endif>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-4">
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
                <div class="row">
                    <div class="form-group col-sm-4"></div>
                    <div class="form-group col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="time_from" class="datepicker form-control" placeholder="Ngày đăng kí từ" autocomplete="off" value="{{ $search_data->time_from }}">
                        </div>
                    </div>
                    <div class="form-group col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="time_to" class="datepicker form-control" placeholder="Ngày đăng kí đến" autocomplete="off" value="{{ $search_data->time_to }}">
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
                        <th>Link</th>
                        <th>Vị trí hiển thị</th>
                        <th width="120">Ngôn ngữ</th>
                        <th width="125">Ảnh</th>
                        <th width="100">Ngày tạo</th>
                        <th width="55">Lệnh</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td align="center">{{ $item->id }}</td>
                        <td>
                            <small>Small Title: &nbsp; {!! $item->small_title_top !!}</small><br>
                            <strong>Big Title: &nbsp;{!! $item->big_title !!}</strong><br>
                            <small>Small Title: &nbsp; {!! $item->small_title_bottom !!}</small>
                        </td>
                        <td>
                            @if(!empty($item->link))
                                <a href="{{ $item->link }}" target="_blank">{{ $item->link }}</a>
                            @endif
                        </td>
                        <td>
                            @php($positions = $item->positions())
                            @if($positions)
                                @foreach($positions as $v)
                                    {{ (!$loop->first?', ':'').$v }}
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $item->lang() }}</td>
                        <td align="center">
                            @if($item->image != '')
                                <img src="{{ $item->getImageUrl('small') }}" width="100" />
                            @endif
                        </td>
                        <td align="center">{{ \Lib::dateFormat($item->created, 'd/m/Y H:i:s') }}</td>
                        <td align="center">
                            @if(\Lib::can($permission, 'edit'))
                                @if($item->status == 2)
                                    <a href="javascript:void(0)" class="text-primary" onclick="shop.admin.updateStatus({{ $item->id }},false,'f_small')" title="Đang hiển thị, Click để ẩn"><i class="fe-check-circle"></i></a>
                                @else
                                    <a href="javascript:void(0)" class="text-secondary" onclick="shop.admin.updateStatus({{ $item->id }}, true,'f_small')" title="Đang ẩn, Click để hiển thị"><i class="fe-check-circle"></i></a>
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