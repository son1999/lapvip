@extends('BackEnd::layouts.default')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="mb-5"><h1>Thông tin Log {{ $site_title }}</h1></div>

        {!! Form::open(['url' => route('admin.user.log', $search_data->id), 'method' => 'get', 'id' => 'searchForm']) !!}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="time_from" class="datepicker form-control" placeholder="Từ ngày" autocomplete="off" value="{{ $search_data->time_from }}">
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="time_to" class="datepicker form-control" placeholder="Đến ngày" autocomplete="off" value="{{ $search_data->time_to }}">
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
                        <th width="100">Thời gian</th>
                        <th>Hành động</th>
                        <th width="100">ID liên kết</th>
                        <th width="310">Link</th>
                        <th width="100">Thiết bị</th>
                        <th width="130">IP</th>
                        <th>Ghi chú</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $log)
                    <tr>
                        <td align="center">{{ \Lib::dateFormat($log->created, 'd/m/Y H:i:s') }}</td>
                        <td>{{ $log->getAction() }}</td>
                        <td>{{ $log->object_id }}</td>
                        <td>
                            @if(!empty($log->url))
                                <a href="{{ $log->url }}" title="{{ $log->url }}" target="_blank">{{ str_limit($log->url, 40) }}</a>
                            @endif
                        </td>
                        <td>{{ $log->getDevice() }}</td>
                        <td>{{ $log->ip }}</td>
                        <td>{{ $log->note }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

                @if($data->isEmpty())
                    <h4 align="center">Không tìm thấy dữ liệu phù hợp</h4>
                @else
                    <div class="pull-right">Tổng cộng: {{ $data->count() }} bản ghi / {{ $data->lastPage() }} trang</div>
                    {!! $data->links('BackEnd::layouts.pagin') !!}
                @endif
            </div>
        </div>
    </div>
    <!--/.col-->
</div>
@stop