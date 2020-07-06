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
{{--            <a id="booking_search_collapse" class="btn btn-success mb-3 collapsed" data-toggle="collapse" href="#searchForm" aria-expanded="false" aria-controls="searchForm">Tìm kiếm</a>--}}

{{--            {!! Form::open(['url' => route('admin.'.$key), 'method' => 'get','class' => 'collapse', 'id' => 'searchForm']) !!}--}}
{{--            <div class="card" id="collapseExample">--}}
{{--                <div class="card-body">--}}
{{--                    <div class="row">--}}
{{--                        <div class="form-group col-sm-3">--}}
{{--                            <div class="input-group">--}}
{{--                                <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>--}}
{{--                                <input type="text" name="code" class="form-control" placeholder="Mã đơn hàng"--}}
{{--                                       value="{{ $search_data->code }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-sm-3">--}}
{{--                            <div class="input-group">--}}
{{--                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>--}}
{{--                                <input type="text" name="fromTime" class="datepicker form-control"--}}
{{--                                       placeholder="Ngày tạo đơn từ" autocomplete="off"--}}
{{--                                       value="{{ $search_data->fromTime }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-sm-3">--}}
{{--                            <div class="input-group">--}}
{{--                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>--}}
{{--                                <input type="text" name="toTime" class="datepicker form-control"--}}
{{--                                       placeholder="Ngày tạo đơn đến" autocomplete="off"--}}
{{--                                       value="{{ $search_data->toTime }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="row">--}}
{{--                        <div class="form-group col-sm-3">--}}
{{--                            <div class="input-group">--}}
{{--                                <span class="input-group-addon"><i class="fe-mail"></i></span>--}}
{{--                                <input type="text" name="email" class="form-control" placeholder="Email"--}}
{{--                                       value="{{ $search_data->email }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-sm-3">--}}
{{--                            <div class="input-group">--}}
{{--                                <span class="input-group-addon"><i class="fa fa-phone"></i></span>--}}
{{--                                <input type="text" name="phone" class="form-control" placeholder="Số điện thoại"--}}
{{--                                       value="{{ $search_data->phone }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-sm-3">--}}
{{--                            <div class="input-group">--}}
{{--                                <span class="input-group-addon"><i class="fa fa-user-secret"></i></span>--}}
{{--                                <select id="user_id" name="user_id" class="form-control">--}}
{{--                                    <option value="">-- Người xử lý --</option>--}}
{{--                                    @foreach($users as $v)--}}
{{--                                        <option value="{{ $v['id'] }}"--}}
{{--                                                @if($search_data->user_id == $v['id']) selected="selected" @endif>{{ $v['user_name'] }} {{ !empty($v['fullname']) ? '('.$v['fullname'].')' : '' }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="card-footer">--}}
{{--                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            {!! Form::close() !!}--}}

            <ul class="nav nav-tabs" role="tablist" style="overflow-x: scroll;flex-wrap: nowrap;">
                <li class="nav-item">
                    <a style="display: block;min-width: max-content;" class="nav-link @if(request()->status == 0) active @endif" href="{{route('admin.order_installment',['status' => 0, 'id'=>'new'])}}" >Đơn mới</a>
                </li>
                <li class="nav-item">
                    <a style="display: block;min-width: max-content;" class="nav-link @if(request()->status == 1) active @endif"  href="{{route('admin.order_installment',['status' => 1, 'id'=>'processing'])}}" >Đang xử lý</a>
                </li>
                <li class="nav-item">
                    <a style="display: block;min-width: max-content;" class="nav-link @if(request()->status == 2) active @endif" href="{{route('admin.order_installment',['status' => 2, 'id'=>'progress'])}}" >Đang trong tiến trình trả góp</a>
                </li>
                <li class="nav-item">
                    <a style="display: block;min-width: max-content;" class="nav-link @if(request()->status == 3) active @endif" href="{{route('admin.order_installment',['status' => 3, 'id'=>'finish'])}}" >Hoàn thành</a>
                </li>
                <li class="nav-item">
                    <a style="display: block;min-width: max-content;" class="nav-link @if(request()->status == -1) active @endif" href="{{route('admin.order_installment',['status' => -1, 'id'=>'cancel'])}}" >Đơn hủy</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr class="bg-success">
                                <th scope="col">#</th>
                                <th scope="col">Khách hàng</th>
                                <th scope="col">Chi tiết</th>
                                <th scope="col">Ngày đặt</th>
                                <th scope="col">Phương thức</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($data))
                                @foreach($data as $item_new)
                                    <tr>
                                        <th scope="row">{{$loop->index+1}}</th>
                                        <td>
                                            <b>Name:</b> {{ \Lib::str_limit($item_new->name, 100) }}<br>
                                            <hr>
                                            <b>Date of Birth:</b> {{ $item_new->date_of_birth }}<br>
                                            <hr>
                                            <b>Phone:</b> {{ $item_new->phone }}
                                        </td>
                                        <td>
                                            @if(!empty($item_new->product))
                                                <b>Sản phẩm:</b> {{ $item_new->product->title }}<br>
                                            @endif
                                            <hr>
                                            @if(!empty($item_new->filter_key))
                                                @php $metas = json_decode($item_new->filter_key) @endphp
                                                @foreach($metas as $meta)
                                                    <b>{{$meta->filter_cate_title}}:</b> {{$meta->filter_value}}
                                                    <br><hr>
                                                @endforeach
                                            @endif
                                            <b>Amount:</b> {{ $item_new->quan}}<br>
                                            <hr>
                                                <b>Installment Scenarios:</b> {{$item_new->month}} tháng
                                                <br> <hr>
                                            @if(!empty($item_new->warehouse))
                                                <b>Warehouse:</b> {{ $item_new->warehouse->title }}
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-success text-success"><i class="mdi mdi-clock-in"></i> {{ \Lib::dateFormat($item_new->created, 'd/m/Y H:i:s') }}</span>
                                            <hr>
                                        </td>
                                        <td>
                                            @if($item_new->type == 1)
                                                <span class="badge bg-soft-success text-danger">TRẢ GÓP BẰNG THẺ VISA, MASTER</span>
                                                <hr>
                                            @elseif($item_new->type == 0)
                                                <span class="badge bg-soft-success text-danger">TRẢ GÓP QUA CÔNG TY TÀI CHÍNH</span>
                                                <hr>
                                            @endif
                                        </td>
                                        <td>
                                            <span><i class="fe-star-on text-danger"></i> <u>@if($item_new->status == 0) ĐƠN MỚI @elseif($item_new->status == 1) ĐANG XỬ LÝ @elseif($item_new->status == 2) ĐANG TRONG TIẾN TRÌNH TRẢ GÓP @elseif($item_new->status == 3) HOÀN THÀNH @elseif($item_new->status == -1) ĐÃ HỦY @endif</u></span>
                                            <hr>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" class="btn btn-primary text-light mt-2" onclick="shop.admin.Processing({{ $item_new->id }},true,'order_installment')" title="Tiếp nhận, và tiến hành xử lý"><i class="fe-play-circle"></i></a><br>
                                            <a href="{{ route('admin.'.$key.'.view', $item_new->id) }}" class="btn btn-danger text-light mt-2"  title="Xem chi tiết đơn hang"><i class="fe-eye"></i></a><br>
                                            <a href="{{ route('admin.'.$key.'.delete', $item_new->id) }}" class="btn btn-danger text-light mt-2" onclick="return confirm('Bạn muốn hủy đơn ?')" title="Hủy đơn"><i class="fe-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-12">
                            @if(empty($data) || $data->isEmpty())
                                <h4 align="center">Không tìm thấy dữ liệu phù hợp</h4>
                            @else
                                @if(!empty($data) && \Lib::can($permission, 'export'))
                                    <div class="col-sm-6">
                                        <a href="{{route('export.orderInstallment', request()->query())}}?status={{request()->status}}" class="w-100 btn btn-primary text-light"><i class="fe-download-cloud fa-2x"></i> &nbsp;&nbsp; Xuất File Excel</a>
                                    </div>
                                @endif
                                <div class="pull-right">Tổng cộng: {{ $data->count() }} bản ghi / {{ $data->lastPage() }} trang</div>
                                {!! $data->links('BackEnd::layouts.pagin') !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/.col-->
    </div>
@stop