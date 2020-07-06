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
                                <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                                <input type="text" name="code" class="form-control" placeholder="Mã đơn hàng"
                                       value="{{ $search_data->code }}">
                            </div>
                        </div>
{{--                        <div class="form-group col-sm-3">--}}
{{--                            <div class="input-group">--}}
{{--                                <span class="input-group-addon"><i class="fa fa-filter"></i></span>--}}
{{--                                <select id="type" name="type" class="form-control">--}}
{{--                                    <option value="">-- Phân loại Booking --</option>--}}
{{--                                    @foreach($type as $k => $v)--}}
{{--                                        <option value="{{ $k }}"--}}
{{--                                                @if($search_data->type == $k) selected="selected" @endif>{{ $v }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="form-group col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                <select id="status" name="status" class="form-control">
                                    <option value="">-- Chọn trạng thái --</option>
                                    <option value="-99"
                                            @if($search_data->status != '' && $search_data->status == -99) selected="selected" @endif>
                                        Đang xử lý
                                    </option>
                                    @foreach($booking_status as $k => $v)
                                        <option value="{{ $k }}"
                                                @if($search_data->status == $k) selected="selected" @endif>{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" name="fromTime" class="datepicker form-control"
                                       placeholder="Ngày tạo đơn từ" autocomplete="off"
                                       value="{{ $search_data->fromTime }}">
                            </div>
                        </div>
                        <div class="form-group col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" name="toTime" class="datepicker form-control"
                                       placeholder="Ngày tạo đơn đến" autocomplete="off"
                                       value="{{ $search_data->toTime }}">
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
{{--                        <div class="form-group col-sm-3">--}}
{{--                            <div class="input-group">--}}
{{--                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>--}}
{{--                                <input type="text" name="startTimeFrom" class="datepicker form-control"--}}
{{--                                       placeholder="Ngày đặt bàn từ" autocomplete="off"--}}
{{--                                       value="{{ $search_data->startTimeFrom }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-sm-3">--}}
{{--                            <div class="input-group">--}}
{{--                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>--}}
{{--                                <input type="text" name="startTimeTo" class="datepicker form-control"--}}
{{--                                       placeholder="Ngày đặt bàn đến" autocomplete="off"--}}
{{--                                       value="{{ $search_data->startTimeTo }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="form-group col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user-secret"></i></span>
                                <select id="user_id" name="user_id" class="form-control">
                                    <option value="">-- Người xử lý --</option>
                                    @foreach($users as $v)
                                        <option value="{{ $v['id'] }}"
                                                @if($search_data->user_id == $v['id']) selected="selected" @endif>{{ $v['user_name'] }} {{ !empty($v['fullname']) ? '('.$v['fullname'].')' : '' }}</option>
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

            <ul class="nav nav-tabs">
                <li class="nav-item" id="tab_order_news">
                    <a class="nav-link {{$search_data->status == 1 && $search_data->receive != 1? 'active' : ''}}"
                       href="{{route('admin.order',['status' => 1])}}">Đơn mới</a>
                </li>
                <li class="nav-item" id="tab_order_received">
                    <a class="nav-link {{$search_data->status == 1 && $search_data->receive == 1  ? 'active' : ''}}"
                       href="{{route('admin.order',['status' => 1, 'receive' => 1])}}">Đơn đã tiếp nhận</a>
                </li>
                <li class="nav-item" id="tab_order_shipping">
                    <a class="nav-link {{$search_data->status == 3? 'active' : ''}}"
                       href="{{route('admin.order',['status' => 3])}}">Đơn chờ thanh toán</a>
                </li>
                <li class="nav-item" id="tab_order_paid">
                    <a class="nav-link {{$search_data->status == 4 ? 'active' : ''}}"
                       href="{{route('admin.order',['status' => 4])}}">Đơn đã thanh toán</a>
                </li>
                {{--<li class="nav-item">--}}
                    {{--<a class="nav-link {{$search_data->status == 0 ? 'active' : ''}}"--}}
                       {{--href="{{route('admin.order',['status' => 0])}}">Đơn hết phòng</a>--}}
                {{--</li>--}}
                <li class="nav-item" id="tab_order_completed">
                    <a class="nav-link {{$search_data->status == 1000 ? 'active' : ''}}"
                       href="{{route('admin.order',['status' => 1000])}}">Đơn hoàn thành</a>
                </li>
                <li class="nav-item" id="tab_order_refund">
                    <a class="nav-link {{$search_data->status == 5 ? 'active' : ''}}"
                       href="{{route('admin.order',['status' => 5])}}">Đơn hoàn</a>
                </li>
                {{--<li class="nav-item">--}}
                {{--<a class="nav-link disabled" href="#">Disabled</a>--}}
                {{--</li>--}}
            </ul>

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
                                <th width="150">Mã đơn</th>
                                <th width="250">Khách hàng</th>
                                <th>Chi tiết</th>
                                <th width="100">Ngày đặt</th>
                                <th width="100">Thanh toán</th>
                                <th width="100">Trạng thái</th>
                                <th>Ghi chú</th>
                                <th width="55">Lệnh</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($order_details = [])
                            @foreach ($data as $item)
                                <tr @if($item->status != 2) style="background-color:{{ ($item->status==-1)?'#f8d7da':($item->status==1&&$item->user_id==0?'#fff3cd':'transparent') }}" @endif>
                                    <td align="center">{{ $item->id }}</td>
                                    <td>{{ $item->code }}</td>
                                    <td>
                                        <b>N:</b> {{ \Lib::str_limit($item->fullname, 50) }}<br>
                                        <b>E:</b> {{ $item->email }}<br>
                                        <b>P:</b> {{ $item->phone }}
                                    </td>
                                    <td>
                                        @if(!empty($item->items))
                                            @php($count = 0)
                                            @php($quantity=0)
                                            @foreach($item->items as $d)
                                                @php($quantity += $d->quantity)
                                                @php($count += 1)
                                            @endforeach
                                            <div><i class="fa fa-apple"></i> <b>{{ \Lib::numberFormat($count)}}</b> loại sp/ sl: <b>{{ \Lib::numberFormat($quantity)}}</b></div>
                                            <p>Phí ship: <b class="text-danger">{{ $item->fee_shipping > 0 ? \Lib::priceFormatEdit($item->fee_shipping)['price'] : '---' }}<sup class="text-danger">đ</sup></b></p>
                                            <p>Tổng: <b class="text-danger">{{ $item->total_price > 0 ? \Lib::priceFormatEdit($item->total_price + $item->fee_shipping)['price'] : '---' }}<sup class="text-danger">đ</sup></b></p>
                                        @endif
                                    </td>
                                    <td align="center">{{ \Lib::dateFormat($item->created, 'd/m/Y H:i:s') }}</td>
                                    <td>
                                        {{$item->paymentTypeName()}}
                                        @if($item->payment_type==1)
                                        <p><a href="javascript:void(0)" onclick="order.checkOrderBizPay('{{$item->code}}','order/bizpay-order')">Kiểm tra đơn từ BizPay</a> </p>
                                        @endif
                                    </td>
                                    <td align="center">
                                        {{ $item->status() }}<br/>
                                        <hr>
                                        {{$item->statusCus()}}
                                        <br/>
                                        @if(($item->user_id == \Auth::id()) || $item->payment_type == 1)
                                            @if($item->status == 1 && $item->user_id != 0)
                                                <a href="javascript:void(0)" @click="confirmOrderPendingPaid({{$item->id}},'order/confirm_pending_paid')"
                                                   class="{{($item->status== 3 &&$item->user_id==\Auth::id()) ? 'text-success' : 'text-primary' }}"
                                                   title="Chờ thanh toán"><i class="fa fa-dollar" aria-hidden="true"></i></a>
                                            @elseif($item->status == 3)
                                                <a href="javascript:void(0)" @click="confirmOrderPaid({{$item->id}},'order/confirm_paid')"
                                                   class="{{($item->status== 4 && $item->user_id==\Auth::id()) ? 'text-success' : 'text-primary' }}"
                                                   title="Đã thanh toán"><i class="fa fa-money" aria-hidden="true"></i></a>

                                                @if($item->status_for_cus <= 2)
                                                <a href="javascript:void(0)" @click="confirmOrderTransport({{$item->id}},'order/confirm_transport')"
                                                   class="{{($item->status_for_cus == 2) ? 'text-success' : 'text-primary' }}"
                                                   title="Đang vận chuyển"><i class="fa fa fa-truck" aria-hidden="true"></i></a>
                                                @endif
                                                <a href="javascript:void(0)" @click="confirmOrderDelivered({{$item->id}},'order/confirm_delivered')"
                                                   class="{{($item->status_for_cus == 3) ? 'text-success' : 'text-primary' }}"
                                                   title="Giao hàng thành công"><i class="fa fa-handshake-o" aria-hidden="true"></i></a>
                                            @elseif($item->status == 4)
                                                <a href="javascript:void(0)" @click="confirmOrder({{$item->id}},'order/confirm')"
                                                   class="{{($item->status==2&&$item->user_id==\Auth::id()) ? 'text-success' : 'text-primary' }}"
                                                   title="Xác nhận hoàn thành"><i class="icon-check icons"></i></a>
                                                @if($item->status_for_cus <= 2)
                                                <a href="javascript:void(0)" @click="confirmOrderTransport({{$item->id}},'order/confirm_transport')"
                                                   class="{{($item->status_for_cus== 2) ? 'text-success' : 'text-primary' }}"
                                                   title="Đang vận chuyển"><i class="fa fa fa-truck" aria-hidden="true"></i></a>
                                                @endif
                                                <a href="javascript:void(0)" @click="confirmOrderDelivered({{$item->id}},'order/confirm_delivered')"
                                                   class="{{($item->status_for_cus== 3) ? 'text-success' : 'text-primary' }}"
                                                   title="Giao hàng thành công"><i class="fa fa-handshake-o" aria-hidden="true"></i></a>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->user_id > 0 && !empty($item->user))
                                            <div class="{{$item->status==2?'text-success':'text-danger'}}">
                                                <b>{{ $item->user->user_name }}</b> {{ $item->status == 2 ? 'đã' : 'đang' }}
                                                xử lý
                                                @if($item->user_id != \Auth::user()->id && \Lib::can($permission, 'assign'))
                                                    - <a href="javascript:void(0)" @click="assignOrder({{$item->id}},3,'order/assign')">Tiếp nhận lại</a>
                                                @endif
                                            </div>
                                        @endif
                                        @if($item->note != '' || $item->wepay_note!='')
                                        <div style="word-break: break-word;">Ghi chú: {{ $item->note }} {{$item->wepay_note}}</div>
                                        @endif
                                    </td>
                                    <td align="center">
                                        <div><a href="{{ route('admin.'.$key.'.log', $item->id) }}" class="btn btn-primary" title="Xem lịch sử đơn hàng"><i class="icon-info icons"></i></a></div>
                                        <div class="mt-2">
                                            <a href="{{ route('admin.'.$key.'.view', $item->id) }}" class="btn btn-primary" title="Xem chi tiết hóa đơn"><i class="icon-magnifier icons"></i></a>
                                        </div>
                                        @if(\Lib::can($permission, 'edit') && $item->status != 2)
                                            @if($item->user_id == 0)
                                                <div class="mt-2">
                                                    <a href="javascript:void(0)" @click="assignOrder({{$item->id}},1,'order/assign')" class="btn btn-primary" title="Tiếp nhận đơn hàng "><i class="icon-call-in icons"></i></a>
                                                </div>
                                            @elseif($item->user_id == \Auth::id() || \Lib::can($permission, 'assign'))
                                                <div class="mt-2">
                                                    <a href="javascript:void(0)" @click="assignOrder({{$item->id}},0,'order/assign')" class="btn btn-danger" title="Bỏ tiếp nhận đơn hàng"><i class="icon-call-end icons"></i></a>
                                                </div>
                                                @if($item->status != -1)
                                                    {{--<div class="mt-2">--}}
                                                        {{--<a href="{{ route('admin.'.$key.'.edit', $item->id) }}" class="btn btn-primary" title="Cập nhật đơn hàng "><i class="fe-edit"></i></a>--}}
                                                    {{--</div>--}}
                                                @endif
                                            @endif
                                        @endif
                                        {{--đã thanh toán mới đc hoàn--}}
                                        @if(\Lib::can($permission, 'edit') && $item->payment_status == 1 && ($item->status==2 || $item->status==4) )
                                            <div class="mt-2">
                                                <a href="javascript:void(0)" class="btn btn-warning "  title="Hoàn đơn hàng " onclick="order.showModalRefundOrder({{ $item->id }})"><i class="icon-plane icons"></i></a>
                                            </div>
                                        @endif
                                        {{----}}
                                        @if(\Lib::can($permission, 'refund') && $item->status == 5)
                                            <div class="mt-2">
                                                <a href="javascript:void(0)" class="btn btn-warning "  title="Xác nhận hoàn đơn hàng " onclick="order.showModalConfirmRefundOrder({{ $item->id }})"><i class="icon-plane icons"></i></a>
                                            </div>
                                        @endif
                                        @if(\Lib::can($permission, 'refund') && $item->status == 6)
                                            <div class="mt-2">
                                                <a href="javascript:void(0)" class="btn btn-success "  title="Hoàn thành hoàn đơn hàng " onclick="order.DoneRefundOrder({{ $item->id }},'order/done-refund')"><i class="icon-plane icons"></i></a>
                                            </div>
                                        @endif
                                        @if(\Lib::can($permission, 'delete') && $item->status != -1 && $item->status != 2 && $item->status != 4 && $item->user_id > 0)
                                            <div class="mt-2">
                                                <a href="javascript:void(0)" class="btn btn-danger "  title="Hủy đơn hàng " onclick="order.showModalCancelOrder({{ $item->id }},'order/cancel')"><i class="icon-trash icons"></i></a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{--Huy phong--}}
                        <div class="modal fade popup-reason" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Hủy đơn hàng</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="name">Lý do muốn hủy đơn hàng</label>
                                                <input id="order_id" type="hidden">
                                                <input id="reason" class="form-control" type="text" placeholder="Lời nhắn sẽ được gửi đến khách hàng">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" onclick="order.CancelOrder('order/cancel')" class="btn btn-sm btn-secondary"><i class="fa fa-close"></i> Hủy đơn hàng </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--refund phong--}}
                        <div class="modal fade popup-refund-reason" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Hoàn đơn hàng</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="name">Lý do muốn hoàn đơn hàng</label>
                                                <input id="order_id" type="hidden">
                                                <input id="reason_refund" class="form-control" type="text" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" onclick="order.RefundOrder('order/refund')" class="btn btn-sm btn-secondary"><i class="fa fa-plane"></i> Yêu cầu hoàn đơn hàng </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--confirm refund--}}
                        <div class="modal fade popup-confirm-refund" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Xác nhận hoàn đơn hàng</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="name">Ghi chú</label>
                                                <input id="order_id" type="hidden">
                                                <input id="refund_note" class="form-control" type="text" placeholder="Ghi chú">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" onclick="order.ConfirmRefundOrder('order/confirm-refund')" class="btn btn-sm btn-secondary"><i class="fa fa-close"></i> Xác nhận hoàn</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--wepay order refund--}}
                        <div class="modal fade popup-order-wepay" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Kiểm tra đơn hàng BizPay</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="order_wepay" style="min-height: 40px">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pull-right">Tổng cộng: {{ $data->count() }} bản ghi / {{ $data->lastPage() }}trang
                    </div>

                    {!! $data->links('BackEnd::layouts.pagin') !!}
                </div>
            </div>
        </div>
        <!--/.col-->
    </div>
@stop
@section('js_bot')
    <script>
        // shop.ready.add(function () {
            var details = {!! json_encode($order_details) !!};
            $('.countdown-keeproom').each(function () {
               var time = $(this).data('date');
               var id = $(this).data('id');
                shop.admin.getCountDown(time,id);
            });
        // });
    </script>
    {!! \Lib::addMedia('admin/js/vue.js') !!}
    {!! \Lib::addMedia('admin/js/order.js') !!}
    @include('BackEnd::pages.order.components.intro')
@stop