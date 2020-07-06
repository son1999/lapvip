@extends('BackEnd::layouts.default')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-5"><h1>{{ $site_title }} #{{ $order->id }}</h1></div>

            {!! Form::open(['url' => route('admin.'.$key.'.log', $search_data->id), 'method' => 'get', 'id' => 'searchForm']) !!}
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
                            <th>Người dùng</th>
                            <th>Hành động</th>
                            <th>Thời gian</th>
                            <th>IP</th>
                            <th>Ghi chú</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $log)
                            <tr>
                                <td>{{ $log->username }}</td>
                                <td>{{ $log->action() }}</td>
                                <td align="center">{{ \Lib::dateFormat($log->created, 'd/m/Y H:i:s') }}</td>
                                <td>{{ $log->ip }}</td>
                                <td style="word-break: break-all;width: 50%">{{ $log->note }}</td>
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