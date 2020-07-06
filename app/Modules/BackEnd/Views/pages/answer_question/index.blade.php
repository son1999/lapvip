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
                                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                <select id="status" name="status" class="form-control">
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
                    <div id="vue-order">
                        <table class="table table-bordered table-striped table-responsive">
                            <thead>
                            <tr>
                                <th width="55">ID</th>
                                <th width="250">Câu hỏi</th>
                                <th width="100">Sản phẩm</th>
                                <th width="100">Ngày tạo</th>
                                <th width="55">Lệnh</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data as $item)
                                <tr >
                                    <td align="center">{{ $item->id }}</td>
                                    <td align="center">{!! $item->question !!}</td>
                                    <td>

                                        <b>Code:</b> {{ $item->product['id'] }}<br>
                                        <b>Name:</b> {{ $item->product['title'] }}

                                    </td>
                                    <td align="center">{{ \Lib::dateFormat($item->created, 'd/m/Y H:i:s') }}</td>
                                    <td align="center">
                                        @if(\Lib::can($permission, 'edit'))
                                            <div class="mb-2">
                                                @if($item->status == 2)
                                                    <a href="javascript:void(0)" class="text-primary" onclick="shop.admin.updateStatus({{ $item->id }},false,'answer_question')" title="Đang hiển thị, bấm để ẩn"><i class="fa fa-eye"></i></a>
                                                @else
                                                    <a href="javascript:void(0)" class="text-secondary" onclick="shop.admin.updateStatus({{ $item->id }}, true,'answer_question')" title="Đang ẩn, bấm để hiển thị"><i class="fa fa-eye"></i></a>
                                                @endif
                                            </div>
                                            <div><a href="{{ route('admin.'.$key.'.view', $item->id) }}" class="text-primary" title="Trả lời câu hỏi"><i class="fa fa-paper-plane"></i></a></div>
                                        @endif
                                        @if(\Lib::can($permission, 'delete'))
                                            <div class="mt-2">
                                                <a href="{{ route('admin.'.$key.'.delete', $item->id) }}"  class="text-primary" onclick="return confirm('Bạn muốn xóa ?')"><i class="icon-trash icons"></i></a>
                                            </div>
                                        @endif
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