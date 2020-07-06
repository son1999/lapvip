@extends('BackEnd::layouts.default')

@section('breadcrumb') {!! \Lib::renderBreadcrumb([], true) !!} @stop

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
            <a id="booking_search_collapse" class="btn btn-success mb-3 collapsed" data-toggle="collapse" href="#searchForm" aria-expanded="false" aria-controls="searchForm">Tìm kiếm</a>

            {!! Form::open(['url' => route('admin.'.$key), 'method' => 'get','class' => 'collapse', 'id' => 'searchForm']) !!}
            <div class="card" id="collapseExample">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" name="fromTime" class="datepicker form-control"
                                       placeholder="Ngày tạo" autocomplete="off"
                                       value="{{ $search_data->fromTime }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fe-mail"></i></span>
                                <input type="text" name="email" class="form-control" placeholder="Email"
                                       value="{{ $search_data->email }}">
                            </div>
                        </div>
                        <div class="form-group col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                <input type="text" name="phone" class="form-control" placeholder="Số điện thoại"
                                       value="{{ $search_data->phone }}">
                            </div>
                        </div>
                        <div class="form-group col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user-circle-o"></i></span>
                                <input type="text" name="full_name" class="form-control" placeholder="Họ và Tên"
                                       value="{{ $search_data->fullname }}">
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
                    <div id="vue-order">
                        <table class="table table-bordered table-striped table-responsive">
                            <thead>
                            <tr>
                                <th width="55">ID</th>
                                <th width="250">Khách hàng</th>
                                <th width="100">Ngày tạo</th>
                                <th width="55">Lệnh</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data as $item)
                                <tr >
                                    <td align="center">{{ $item->id }}</td>
                                    <td>
                                        <b>Name:</b> {!! \Lib::str_limit($item->fullname, 50) !!}<br>
                                        <b>Email:</b> {{ $item->email }}<br>
                                        <b>Phone:</b> {{ $item->phone }}
                                    </td>
                                    <td align="center">{{ \Lib::dateFormat($item->created, 'd/m/Y H:i:s') }}</td>
                                    <td align="center">
                                        <div><a href="{{ route('admin.'.$key.'.view', $item->id) }}" class="btn btn-primary" title="Xem chi tiết liên hệ"><i class="icon-info icons"></i></a></div>
                                        <div class="mt-2">
                                            <a href="{{ route('admin.'.$key.'.delete', $item->id) }}"  class="btn btn-primary" onclick="return confirm('Bạn muốn xóa ?')"><i class="icon-trash icons"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                    @if(empty($data) || $data->isEmpty())
                        <h4 align="center">Không tìm thấy dữ liệu phù hợp</h4>
                    @else
                        <div class="pull-right">Tổng cộng: {{ $data->count() }} bản ghi / {{ $data->lastPage() }}trang
                    @endif
                    </div>

                    {!! $data->links('BackEnd::layouts.pagin') !!}
                </div>
            </div>
        </div>
        <!--/.col-->
    </div>
@stop
@section('js_bot')
    {!! \Lib::addMedia('admin/js/order.js') !!}
@stop